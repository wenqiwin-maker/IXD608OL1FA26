
var filterKeys = ["origin", "category", "price", "material", "availability"];

var shop = document.getElementById("shop");
var searchInput = document.querySelector(".site-header .hotdog input[name=search]");

var state = readState();

var lastRequest = 0;
var typingTimer = null;

function readState() {
	var params = new URLSearchParams(location.search);
	var result = {search: params.get("search") || "", sort: params.get("sort") || "featured"};
	filterKeys.forEach(function(key) {
		result[key] = params.get(key) || "";
	});
	return result;
}

function makeQuery(values) {
	var params = new URLSearchParams();
	if (values.search != "") {
		params.set("search", values.search);
	}
	filterKeys.forEach(function(key) {
		if (values[key] != "") {
			params.set(key, values[key]);
		}
	});
	if (values.sort != "featured") {
		params.set("sort", values.sort);
	}
	return params.toString();
}

function linkWith(key, value) {
	var changed = Object.assign({}, state);
	changed[key] = value;
	var query = makeQuery(changed);
	return "product_list.php" + (query ? "?" + query : "");
}

function escapeHtml(text) {
	return String(text).replace(/[&<>"']/g, function(letter) {
		return {"&": "&amp;", "<": "&lt;", ">": "&gt;", "\"": "&quot;", "'": "&#39;"}[letter];
	});
}

function money(amount) {
	amount = Number(amount);
	return "$" + (amount == Math.floor(amount) ? amount : amount.toFixed(2));
}

function productCard(product) {
	return `
	<div class="col-xs-6 col-md-4">
		<figure class="figure product">
			<div class="image-wrap">
				<a href="product_item.php?id=${product.id}"><img src="${escapeHtml(product.image)}" alt="${escapeHtml(product.name)}"></a>
				<div class="favorite heart-overlay"><input type="checkbox" id="favorite-${product.id}"><label for="favorite-${product.id}">&hearts;</label></div>
			</div>
			<figcaption>
				<div class="display-flex"><div class="content-heading flex-stretch">${escapeHtml(product.name)}</div><div class="price flex-none">${money(product.price)}</div></div>
				<div class="supporting">${escapeHtml(product.origin)}</div>
			</figcaption>
		</figure>
	</div>`;
}

function menuTemplate(label, key, choices, extraClass) {
	var chosen = choices.find(function(choice) {
		return choice.value == state[key];
	});
	var isFilter = key != "sort";
	var pillText = escapeHtml(label) + (chosen && isFilter ? ": " + escapeHtml(chosen.label) : "");
	if (!isFilter) {
		pillText = "Sort <strong>" + escapeHtml(chosen ? chosen.label : "Featured") + "</strong>";
	}

	var links = choices.map(function(choice) {
		return `<a href="${linkWith(key, choice.value)}" data-key="${key}" data-value="${escapeHtml(choice.value)}">${escapeHtml(choice.label)}</a>`;
	}).join("");
	if (isFilter) {
		links = `<a href="${linkWith(key, "")}" data-key="${key}" data-value="">Any</a>` + links;
	}

	return `
	<details class="menu ${extraClass}">
		<summary class="pill ${isFilter && chosen ? "active" : ""}">${pillText} <span class="arrow">&#9662;</span></summary>
		<div class="menu-list">${links}</div>
	</details>`;
}

function filterBar(data) {
	var anyFilter = state.search != "" || filterKeys.some(function(key) {
		return state[key] != "";
	});

	var menus = data.menus.map(function(menu) {
		return menuTemplate(menu.label, menu.key, menu.choices, "");
	}).join("");

	return `
	<a href="product_list.php" data-clear class="pill ${anyFilter ? "" : "active"}">All</a>
	${menus}
	<div class="flex-stretch"></div>
	${menuTemplate("Sort", "sort", data.sorts, "menu-right")}`;
}

function chips(data) {
	var list = [];
	if (state.search != "") {
		list.push({key: "search", text: "“" + state.search + "”"});
	}
	data.menus.forEach(function(menu) {
		var chosen = menu.choices.find(function(choice) {
			return choice.value == state[menu.key];
		});
		if (chosen) {
			list.push({key: menu.key, text: menu.label + ": " + chosen.label});
		}
	});

	if (list.length == 0) {
		return "";
	}

	return list.map(function(chip) {
		return `<button type="button" class="chip" data-key="${chip.key}" data-value="" aria-label="Remove ${escapeHtml(chip.text)}">${escapeHtml(chip.text)} <span aria-hidden="true">&times;</span></button>`;
	}).join("") + ` <a href="product_list.php" data-clear class="link-underline">Clear all</a>`;
}

function draw(data) {
	var grid = document.getElementById("product-grid");

	document.getElementById("filter-bar").innerHTML = filterBar(data);
	document.getElementById("active-filters").innerHTML = chips(data);

	var summary = data.count + " " + (data.count == 1 ? "object" : "objects");
	if (state.search != "") {
		summary += " for &ldquo;" + escapeHtml(state.search) + "&rdquo;";
	}
	document.getElementById("result-summary").innerHTML = summary;

	if (data.count == 0) {
		grid.innerHTML = `<p>No objects match that search. <a href="product_list.php" data-clear>See everything &rsaquo;</a></p>`;
	} else {
		grid.innerHTML = `<div class="grid gap">${data.products.map(productCard).join("")}</div>`;
	}
}

function showError() {
	document.getElementById("product-grid").innerHTML = `<p>Sorry, the objects couldn't be loaded. <a href="${linkWith("search", state.search)}">Try again</a></p>`;
}

function loadProducts(how) {
	var query = makeQuery(state);
	var thisRequest = ++lastRequest;
	var grid = document.getElementById("product-grid");
	grid.classList.add("is-loading");

	fetch("api.php?action=products" + (query ? "&" + query : ""))
		.then(function(response) {
			if (!response.ok) {
				throw new Error("The API answered with " + response.status);
			}
			return response.json();
		})
		.then(function(data) {
			if (thisRequest != lastRequest) {
				return;
			}

			filterKeys.forEach(function(key) {
				state[key] = data.params[key] || "";
			});
			state.sort = data.params.sort;

			draw(data);
			query = makeQuery(state);
			var address = "product_list.php" + (query ? "?" + query : "");
			if (how == "push") {
				history.pushState(null, "", address);
			} else if (how == "replace") {
				history.replaceState(null, "", address);
			}
		})
		.catch(function(error) {
			console.log(error);
			if (thisRequest == lastRequest) {
				showError();
			}
		})
		.finally(function() {
			if (thisRequest == lastRequest) {
				grid.classList.remove("is-loading");
			}
		});
}

function clearAll() {
	state = {search: "", sort: "featured"};
	filterKeys.forEach(function(key) {
		state[key] = "";
	});
	if (searchInput) {
		searchInput.value = "";
	}
}

document.addEventListener("click", function(event) {
	var choice = event.target.closest("a[data-key], button[data-key]");
	if (choice && shop.contains(choice)) {
		event.preventDefault();
		state[choice.dataset.key] = choice.dataset.value;
		if (choice.dataset.key == "search" && searchInput) {
			searchInput.value = "";
		}
		loadProducts("push");
		return;
	}

	var clear = event.target.closest("[data-clear]");
	if (clear && shop.contains(clear)) {
		event.preventDefault();
		clearAll();
		loadProducts("push");
		return;
	}

	document.querySelectorAll("details.menu[open]").forEach(function(menu) {
		if (!menu.contains(event.target)) {
			menu.removeAttribute("open");
		}
	});
});

shop.addEventListener("toggle", function(event) {
	if (event.target.open) {
		document.querySelectorAll("details.menu[open]").forEach(function(menu) {
			if (menu != event.target) {
				menu.removeAttribute("open");
			}
		});
	}
}, true);

document.addEventListener("keydown", function(event) {
	if (event.key == "Escape") {
		document.querySelectorAll("details.menu[open]").forEach(function(menu) {
			menu.removeAttribute("open");
		});
	}
});

if (searchInput) {
	searchInput.form.addEventListener("submit", function(event) {
		event.preventDefault();
		clearTimeout(typingTimer);
		state.search = searchInput.value.trim();
		loadProducts("push");
	});

	searchInput.addEventListener("input", function() {
		clearTimeout(typingTimer);
		typingTimer = setTimeout(function() {
			state.search = searchInput.value.trim();
			loadProducts("replace");
		}, 250);
	});
}

window.addEventListener("popstate", function() {
	state = readState();
	if (searchInput) {
		searchInput.value = state.search;
	}
	loadProducts("none");
});

loadProducts("replace");

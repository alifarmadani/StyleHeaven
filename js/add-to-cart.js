document.addEventListener("DOMContentLoaded", function() {
    let selectedSize = "M";
    document.querySelectorAll(".size-btn").forEach(button => {
        button.addEventListener("click", function() {
            document.querySelectorAll(".size-btn").forEach(btn => btn.classList.remove("selected"));
            this.classList.add("selected");
            selectedSize = this.getAttribute("data-size");
        });
    });

    let quantityInput = document.getElementById("quantity");
    let maxStock = parseInt(quantityInput.getAttribute("max"));

    document.getElementById("increase").addEventListener("click", function() {
        let quantity = parseInt(quantityInput.value) || 1;
        if (quantity < maxStock) {
            quantityInput.value = quantity + 1;
        }
    });

    document.getElementById("decrease").addEventListener("click", function() {
        let quantity = parseInt(quantityInput.value) || 1;
        if (quantity > 1) {
            quantityInput.value = quantity - 1;
        }
    });

    quantityInput.addEventListener("blur", function() {
        let value = quantityInput.value.trim();
        let numericValue = parseInt(value);

        if (isNaN(numericValue)) {
            quantityInput.value = 1;
        } else if (numericValue < 1) {
            quantityInput.value = 1;
        } else if (numericValue > maxStock) {
            quantityInput.value = maxStock;
        } else {
            quantityInput.value = numericValue;
        }
    });
});


//FUNGSI TAMBAH KERANJANG
document.addEventListener("DOMContentLoaded", function () {
    document.querySelector(".add-to-cart").addEventListener("click", function () {
        let productId = this.getAttribute("data-id");
        let selectedSizeBtn = document.querySelector(".size-btn.active");
        let quantity = document.getElementById("quantity").value;

        if (!selectedSizeBtn) {
            alert("Pilih size terlebih dahulu!");
            return;
        }

        let size = selectedSizeBtn.getAttribute("data-size");

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "add_to_cart.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                if (response.status === "error") {
                    alert(response.message);
                    if (response.message.includes("Silakan login")) {
                        window.location.href = "index.php";
                    }
                } else {
                    alert(response.message); 
                }
            }
        };

        xhr.send(`product_id=${productId}&size=${size}&quantity=${quantity}`);
    });

    document.querySelectorAll(".size-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            document.querySelectorAll(".size-btn").forEach(b => b.classList.remove("active"));
            this.classList.add("active");
        });
    });
});



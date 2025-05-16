document.addEventListener("DOMContentLoaded", function () {
    const incrementButtons = document.querySelectorAll(".quantity-btn.increment");
    const decrementButtons = document.querySelectorAll(".quantity-btn.decrement");

    incrementButtons.forEach(button => {
        button.addEventListener("click", function () {
            const id = this.dataset.id;
            const stock = parseInt(this.dataset.stock);
            const quantitySpan = document.getElementById(`quantity-number-${id}`);
            const currentQuantity = parseInt(quantitySpan.textContent);

            if (currentQuantity >= stock) {
                alert("Stok tidak mencukupi.");
                return;
            }

            fetch('update_quantity.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id=${id}&action=increment`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    quantitySpan.textContent = data.new_quantity;
                } else {
                    alert(data.error);
                }
            });
        });
    });

    decrementButtons.forEach(button => {
        button.addEventListener("click", function () {
            const id = this.dataset.id;
            const quantitySpan = document.getElementById(`quantity-number-${id}`);

            fetch('update_quantity.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id=${id}&action=decrement`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    quantitySpan.textContent = data.new_quantity;
                } else {
                    alert(data.error);
                }
            });
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];

  document.querySelectorAll('.add-to-cart-btn').forEach(button => {
    button.addEventListener('click', (e) => {
      const productBox = e.target.closest('.product-box');
      const id = productBox.dataset.id;
      const name = productBox.dataset.name;
      const price = parseFloat(productBox.dataset.price);
      const qty = parseInt(productBox.querySelector('.qty-input').value);
      const size = productBox.querySelector('input[name="size"]:checked')?.value || "M";
      const image = productBox.dataset.image;

      const existing = cart.find(item => item.id === id && item.size === size);
      if (existing) {
        existing.quantity += qty;
      } else {
        cart.push({ id, name, price, quantity: qty, size, image });
      }

      localStorage.setItem('cart', JSON.stringify(cart));
      alert(`${name} added to cart!`);
    });
  });
});

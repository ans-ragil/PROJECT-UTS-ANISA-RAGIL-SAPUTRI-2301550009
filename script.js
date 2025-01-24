// Event listener untuk form checkout
document.addEventListener('DOMContentLoaded', () => {
  const checkoutForm = document.getElementById('checkout-form');
  if (checkoutForm) {
    checkoutForm.addEventListener('submit', handleCheckout);
  }

  updateCartCount();
});

function handleCheckout(event) {
  event.preventDefault(); // Cegah form dari submit default

  // Ambil data form
  const name = document.getElementById('name').value;
  const email = document.getElementById('email').value;
  const phone = document.getElementById('phone').value;
  const address = document.getElementById('address').value;

  // Validasi form
  if (!name || !email || !phone || !address) {
    alert('Silakan isi semua kolom yang diperlukan.');
    return;
  }

  // Tampilkan ringkasan pesanan dan metode pembayaran
  displayOrderSummary(name, email, phone, address);
  displayPaymentMethods();
}

function displayOrderSummary(name, email, phone, address) {
  const orderSummaryElement = document.querySelector('.order-summary');
  orderSummaryElement.innerHTML = `
    <h2>Ringkasan Pesanan</h2>
    <p>Nama: ${name}</p>
    <p>Email: ${email}</p>
    <p>Telepon: ${phone}</p>
    <p>Alamat: ${address}</p>
  `;
}

function displayPaymentMethods() {
  const paymentMethodsElement = document.querySelector('.payment-methods');
  paymentMethodsElement.innerHTML = `
    <h2>Metode Pembayaran</h2>
    <label><input type="radio" name="payment-method" value="credit-card"> Kartu Kredit</label>
    <label><input type="radio" name="payment-method" value="paypal"> PayPal</label>
    <label><input type="radio" name="payment-method" value="bank-transfer"> Transfer Bank</label>
  `;

  // Event listener untuk tombol "Place Order"
  const placeOrderButton = document.getElementById('place-order');
  if (placeOrderButton) {
    placeOrderButton.addEventListener('click', processOrder);
  }
}

function processOrder() {
  alert('Pesanan berhasil dibuat!');
}

// Fungsi untuk menambah item ke keranjang
function addToCart(productName, price) {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  cart.push({ name: productName, price: price });
  localStorage.setItem('cart', JSON.stringify(cart));
  alert(`${productName} seharga Rp ${price.toLocaleString('id-ID')} telah ditambahkan ke keranjang!`);
}

// Fungsi update jumlah produk di ikon keranjang
function updateCartCount() {
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  const cartCount = document.getElementById('cart-count');
  if (cartCount) {
    cartCount.style.display = 'none'; // Sembunyikan jumlah produk
  }
}

// Fungsi pilih ukuran produk
function selectSize(label) {
  const sizeLabels = document.querySelectorAll('.size-options label');
  sizeLabels.forEach(lbl => lbl.classList.remove('active'));
  label.classList.add('active');
}

// Fungsi untuk mengubah jumlah produk
function changeQuantity(amount) {
  const quantityInput = document.getElementById("quantity");
  let currentQuantity = parseInt(quantityInput.value);
  currentQuantity += amount;
  if (currentQuantity < 1) currentQuantity = 1;
  quantityInput.value = currentQuantity;
}

// Fungsi kembali ke halaman produk
function goBack() {
  window.location.href = "produk.html";
}

// Fungsi render keranjang
const cartItems = [];

function renderCart() {
  const cartItemsContainer = document.getElementById('cartItems');
  cartItemsContainer.innerHTML = '';
  cartItems.forEach((item, index) => {
    const productDiv = document.createElement('div');
    productDiv.classList.add('product');
    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.value = index;
    const img = document.createElement('img');
    img.src = item.image;
    img.alt = item.name;
    const nameSpan = document.createElement('span');
    nameSpan.textContent = item.name;
    productDiv.appendChild(checkbox);
    productDiv.appendChild(img);
    productDiv.appendChild(nameSpan);
    cartItemsContainer.appendChild(productDiv);
  });
}

const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Penjualan',
                    data: [12000000, 15000000, 17000000, 20000000, 25000000, 12500000],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo de Ropa | Pago con PayPhone</title>
    <style>
        :root {
            --bg: #f6f7fb;
            --card: #ffffff;
            --text: #151a2d;
            --muted: #6a738e;
            --brand: #0f7bff;
            --brand-2: #095dbe;
            --ok: #0a8f4a;
            --shadow: 0 12px 30px rgba(15, 36, 84, 0.12);
            --radius: 16px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at 10% 0%, #e8f1ff 0%, transparent 40%),
                radial-gradient(circle at 90% 20%, #dff7ef 0%, transparent 45%),
                var(--bg);
        }

        .container {
            max-width: 1080px;
            margin: 0 auto;
            padding: 24px 24px 90px;
        }

        .hero {
            background: linear-gradient(135deg, #0f7bff, #20a4f3);
            color: #fff;
            border-radius: var(--radius);
            padding: 28px;
            box-shadow: var(--shadow);
            margin-bottom: 24px;
            text-align: center;
        }

        .hero h1 {
            margin: 0 0 8px;
            font-size: clamp(1.7rem, 2.6vw, 2.2rem);
        }

        .hero p {
            margin: 0;
            opacity: 0.95;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
        }

        .card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 18px;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .product-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            width: fit-content;
            border-radius: 999px;
            padding: 4px 10px;
            background: #e6f2ff;
            color: #0d58b4;
            font-size: 0.82rem;
        }

        .price {
            font-size: 1.2rem;
            font-weight: 700;
            margin-top: auto;
            margin-bottom: 0;
        }

        .btn {
            margin-top: 0;
            width: 100%;
            border: 0;
            border-radius: 12px;
            padding: 11px 14px;
            background: var(--brand);
            color: #fff;
            cursor: pointer;
            font-weight: 600;
            transition: transform .15s ease, background .15s ease;
        }

        .btn:hover {
            background: var(--brand-2);
            transform: translateY(-1px);
        }

        .card form {
            margin-top: 10px;
        }

        .cart-toggle {
            position: fixed;
            right: 20px;
            bottom: 20px;
            z-index: 40;
            border: 0;
            border-radius: 999px;
            padding: 12px 16px;
            background: #0f7bff;
            color: #fff;
            font-weight: 700;
            box-shadow: 0 14px 30px rgba(15, 123, 255, 0.35);
            cursor: pointer;
        }

        .cart-toggle span {
            display: inline-block;
            min-width: 24px;
            margin-left: 8px;
            padding: 2px 8px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.25);
        }

        .cart-overlay {
            position: fixed;
            inset: 0;
            background: rgba(7, 16, 37, 0.4);
            opacity: 0;
            pointer-events: none;
            transition: opacity .2s ease;
            z-index: 50;
        }

        .cart-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }

        .cart-panel {
            position: fixed;
            right: 0;
            top: 0;
            height: 100vh;
            width: min(430px, 94vw);
            background: #fff;
            box-shadow: -12px 0 30px rgba(11, 27, 61, 0.2);
            transform: translateX(100%);
            transition: transform .25s ease;
            z-index: 60;
            display: flex;
            flex-direction: column;
        }

        .cart-panel.open {
            transform: translateX(0);
        }

        .cart-head {
            padding: 18px;
            border-bottom: 1px solid #e8edf7;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cart-head h2 {
            margin: 0;
            font-size: 1.1rem;
        }

        .close-cart {
            border: 0;
            border-radius: 8px;
            background: #edf3ff;
            color: #1c4ea5;
            padding: 6px 10px;
            cursor: pointer;
            font-weight: 700;
        }

        .cart-items {
            list-style: none;
            margin: 0;
            padding: 14px 18px;
            overflow: auto;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .cart-item {
            border: 1px solid #e8edf7;
            border-radius: 10px;
            padding: 10px;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 8px;
            align-items: center;
        }

        .cart-item-title {
            font-weight: 600;
        }

        .cart-item-meta {
            color: var(--muted);
            font-size: 0.9rem;
        }

        .remove-btn {
            border: 0;
            border-radius: 8px;
            background: #ffecee;
            color: #b22b3d;
            padding: 6px 8px;
            cursor: pointer;
            font-weight: 600;
        }

        .cart-empty {
            color: var(--muted);
            font-size: 0.95rem;
            border: 1px dashed #cfdbf2;
            border-radius: 10px;
            padding: 16px;
            text-align: center;
        }

        .cart-foot {
            border-top: 1px solid #e8edf7;
            padding: 14px 18px 18px;
            display: grid;
            gap: 10px;
        }

        .cart-total {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 700;
            font-size: 1.05rem;
        }

        .checkout-form {
            margin: 0;
        }

        .checkout-btn {
            width: 100%;
            border: 0;
            border-radius: 12px;
            padding: 12px;
            background: #0f7bff;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        .checkout-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .note {
            margin-top: 18px;
            color: var(--muted);
            font-size: 0.92rem;
        }

        .ok {
            color: var(--ok);
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <section class="hero">
            <h1>Catalogo de Ropa</h1>
            <p>Encuentra y elegancia en cada pieza  </p>
        </section>

        <section class="grid" aria-label="Productos">
            <article class="card">
                <div class="product-head">
                    <span class="tag">Nueva coleccion</span>
                </div>
                <h3>Camiseta</h3>
                <p>Basica de algodon, talla unica.</p>
                <p class="price">$10.00</p>
                <button type="button" class="btn add-cart" data-product="camiseta" data-price="1000" data-name="Camiseta">Agregar al carrito</button>
            </article>

            <article class="card">
                <div class="product-head">
                    <span class="tag">Mas vendido</span>
                </div>
                <h3>Pantalon</h3>
                <p>Denim clasico, color azul.</p>
                <p class="price">$25.00</p>
                <button type="button" class="btn add-cart" data-product="pantalon" data-price="2500" data-name="Pantalon">Agregar al carrito</button>
            </article>

            <article class="card">
                <div class="product-head">
                    <span class="tag">Oferta</span>
                </div>
                <h3>Chaqueta</h3>
                <p>Ligera para clima templado.</p>
                <p class="price">$39.00</p>
                <button type="button" class="btn add-cart" data-product="chaqueta" data-price="3900" data-name="Chaqueta">Agregar al carrito</button>
            </article>

            <article class="card">
                <div class="product-head">
                    <span class="tag">Top semana</span>
                </div>
                <h3>Zapatos</h3>
                <p>Urbano comodo, suela antideslizante.</p>
                <p class="price">$54.00</p>
                <button type="button" class="btn add-cart" data-product="zapatos" data-price="5400" data-name="Zapatos">Agregar al carrito</button>
            </article>

            <article class="card">
                <div class="product-head">
                    <span class="tag">Nuevo</span>
                </div>
                <h3>Gorra</h3>
                <p>Estilo casual con ajuste posterior.</p>
                <p class="price">$15.00</p>
                <button type="button" class="btn add-cart" data-product="gorra" data-price="1500" data-name="Gorra">Agregar al carrito</button>
            </article>

            <article class="card">
                <div class="product-head">
                    <span class="tag">Elegante</span>
                </div>
                <h3>Vestido</h3>
                <p>Corte moderno para toda ocasion.</p>
                <p class="price">$42.00</p>
                <button type="button" class="btn add-cart" data-product="vestido" data-price="4200" data-name="Vestido">Agregar al carrito</button>
            </article>

            <article class="card">
                <div class="product-head">
                    <span class="tag">Confort</span>
                </div>
                <h3>Sudadera</h3>
                <p>Interior suave, ideal para frio.</p>
                <p class="price">$32.00</p>
                <button type="button" class="btn add-cart" data-product="sudadera" data-price="3200" data-name="Sudadera">Agregar al carrito</button>
            </article>

            <article class="card">
                <div class="product-head">
                    <span class="tag">Verano</span>
                </div>
                <h3>Short</h3>
                <p>Ligero y fresco para diario.</p>
                <p class="price">$18.00</p>
                <button type="button" class="btn add-cart" data-product="short" data-price="1800" data-name="Short">Agregar al carrito</button>
            </article>
        </section>
    </div>

    <button class="cart-toggle" id="openCartBtn" type="button">Ver carrito <span id="cartCount">0</span></button>

    <div class="cart-overlay" id="cartOverlay"></div>

    <aside class="cart-panel" id="cartPanel" aria-label="Carrito de compras">
        <header class="cart-head">
            <h2>Tu carrito</h2>
            <button type="button" class="close-cart" id="closeCartBtn">X</button>
        </header>

        <ul class="cart-items" id="cartItems"></ul>

        <footer class="cart-foot">
            <div class="cart-total">
                <span>Total a Pagar</span>
                <span id="cartTotal">$0.00</span>
            </div>

            <!-- Al hacer clic en este boton, el formulario envia el monto total a procesar_pago.php. -->
            <!-- Envío: el campo hidden "amount" contiene el total (en centavos) enviado al servidor. -->
            <form class="checkout-form" action="procesar_pago.php" method="post">
                <input type="hidden" name="product" value="carrito">
                <input type="hidden" name="amount" id="checkoutAmount" value="0">
                <button type="submit" class="checkout-btn" id="checkoutBtn" disabled>Pagar con PayPhone</button>
            </form>
        </footer>
    </aside>

    <script>
        const cart = [];

        const openCartBtn = document.getElementById('openCartBtn');
        const closeCartBtn = document.getElementById('closeCartBtn');
        const cartOverlay = document.getElementById('cartOverlay');
        const cartPanel = document.getElementById('cartPanel');
        const cartItemsEl = document.getElementById('cartItems');
        const cartTotalEl = document.getElementById('cartTotal');
        const cartCountEl = document.getElementById('cartCount');
        const checkoutAmountEl = document.getElementById('checkoutAmount');
        const checkoutBtn = document.getElementById('checkoutBtn');

        const toUsd = (cents) => '$' + (cents / 100).toFixed(2);

        const openCart = () => {
            cartOverlay.classList.add('open');
            cartPanel.classList.add('open');
        };

        const closeCart = () => {
            cartOverlay.classList.remove('open');
            cartPanel.classList.remove('open');
        };

        const renderCart = () => {
            cartItemsEl.innerHTML = '';

            if (cart.length === 0) {
                const li = document.createElement('li');
                li.className = 'cart-empty';
                li.textContent = 'Tu carrito esta vacio.';
                cartItemsEl.appendChild(li);
            } else {
                cart.forEach((item, index) => {
                    const title = item.qty > 1 ? item.name + ' (' + item.qty + ')' : item.name;
                    const subtotal = item.price * item.qty;
                    const li = document.createElement('li');
                    li.className = 'cart-item';
                    li.innerHTML =
                        '<div>' +
                            '<div class="cart-item-title">' + title + '</div>' +
                            '<div class="cart-item-meta">' + toUsd(subtotal) + '</div>' +
                        '</div>' +
                        '<button type="button" class="remove-btn" data-index="' + index + '">Quitar</button>';
                    cartItemsEl.appendChild(li);
                });
            }

            const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            cartTotalEl.textContent = toUsd(total);
            cartCountEl.textContent = String(cart.length);
            // Guarda el total (en centavos) en el input oculto para enviar al servidor.
            checkoutAmountEl.value = String(total);
            checkoutBtn.disabled = total <= 0;
        };

        document.querySelectorAll('.add-cart').forEach((btn) => {
            btn.addEventListener('click', () => {
                const name = btn.dataset.name || 'Producto';
                const product = btn.dataset.product || 'producto';
                const price = Number(btn.dataset.price || '0');
                if (!Number.isFinite(price) || price <= 0) {
                    return;
                }

                const existing = cart.find((item) => item.product === product);
                if (existing) {
                    existing.qty += 1;
                } else {
                    cart.push({ name, product, price, qty: 1 });
                }
                renderCart();
            });
        });

        cartItemsEl.addEventListener('click', (event) => {
            const target = event.target;
            if (!(target instanceof HTMLElement)) {
                return;
            }
            if (!target.classList.contains('remove-btn')) {
                return;
            }

            const index = Number(target.dataset.index || '-1');
            if (index >= 0 && index < cart.length) {
                if (cart[index].qty > 1) {
                    cart[index].qty -= 1;
                } else {
                    cart.splice(index, 1);
                }
                renderCart();
            }
        });

        openCartBtn.addEventListener('click', openCart);
        closeCartBtn.addEventListener('click', closeCart);
        cartOverlay.addEventListener('click', closeCart);

        renderCart();
    </script>
</body>
</html>
@extends('layouts.app')

@section('title', 'Caja')
@section('page_title', 'Caja registradora')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Seleccionar productos</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-2">
                    <input id="product-search" class="form-control" placeholder="Buscar producto...">
                </div>

                <ul id="product-list" class="list-group">
                    @foreach($products as $product)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $product->name }}</strong>
                                <div class="text-muted">{{ number_format($product->price, 2) }}€</div>
                            </div>
                            <div>
                                <input type="number" min="0" step="0.5" class="form-control product-qty" data-id="{{ $product->id }}" data-price="{{ $product->price }}" style="width:100px" placeholder="0">
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-6">
                <h6>Carrito</h6>
                <table class="table" id="cart-table">
                    <thead>
                        <tr><th>Producto</th><th>Cant.</th><th>Precio</th></tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <div class="d-flex justify-content-between">
                    <strong>Total:</strong>
                    <div id="total">0.00</div>
                </div>

                <form id="sale-form" method="POST" action="{{ route('sales.store') }}">
                    @csrf
                    <input type="hidden" name="total" id="input-total" value="0">
                    <input type="hidden" name="paid" id="input-paid" value="0">
                    <input type="hidden" name="items" id="input-items">

                    <div class="mt-3 d-flex gap-2">
                        <button type="button" id="pay-yes" class="btn btn-success">Pagar y confirmar</button>
                        <button type="button" id="pay-no" class="btn btn-secondary">Guardar sin cobrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const products = Array.from(document.querySelectorAll('.product-qty'));
    const searchInput = document.getElementById('product-search');
    const cartTable = document.querySelector('#cart-table tbody');
    const totalEl = document.getElementById('total');
    const inputTotal = document.getElementById('input-total');
    const inputItems = document.getElementById('input-items');
    const form = document.getElementById('sale-form');

    function refreshCart() {
        cartTable.innerHTML = '';
        let total = 0;
        const items = [];
        products.forEach(p => {
            const qty = parseFloat(p.value) || 0;
            if (qty > 0) {
                const id = p.dataset.id;
                const price = parseFloat(p.dataset.price);
                const name = p.closest('li').querySelector('strong').innerText;
                const line = document.createElement('tr');
                line.innerHTML = `<td>${name}</td><td>${qty}</td><td>${(price*qty).toFixed(2)}</td>`;
                cartTable.appendChild(line);
                total += price*qty;
                items.push({product_id: parseInt(id), quantity: qty});
            }
        });
        totalEl.innerText = total.toFixed(2);
        inputTotal.value = total.toFixed(2);
        inputItems.value = JSON.stringify(items);
    }

    products.forEach(p => p.addEventListener('input', refreshCart));

    // Filtrar productos por nombre en la lista
    searchInput.addEventListener('input', () => {
        const q = searchInput.value.trim().toLowerCase();
        products.forEach(p => {
            const li = p.closest('li');
            const name = li.querySelector('strong').innerText.toLowerCase();
            li.style.display = name.includes(q) ? '' : 'none';
        });
    });

    document.getElementById('pay-yes').addEventListener('click', () => {
        if (!confirm('¿Se ha concretado el pago?')) return;
        document.getElementById('input-paid').value = 1;
        form.submit();
    });

    document.getElementById('pay-no').addEventListener('click', () => {
        if (!confirm('Guardar la venta sin marcar como pagada?')) return;
        document.getElementById('input-paid').value = 0;
        form.submit();
    });
</script>
@endsection

<!-- resources/views/checkout_1.blade.php -->
@extends('layouts.customers')
@section('content')

    <style>
        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .modal-body {
            font-size: 1rem;
            margin-bottom: 20px;
        }

        .close {
            color: #aaa;
            font-size: 1.5rem;
            font-weight: bold;
            position: absolute;
            right: 20px;
            top: 20px;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* General styling for the checkout form */
        .checkout__shipping-address {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        .checkout__shipping-header h5 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .checkout__shipping-form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .form-group {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        label {
            font-size: 1rem;
            font-weight: 500;
            color: #444;
            flex: 1;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        select {
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            outline: none;
            flex: 2;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        select:focus {
            border-color: #007bff;
        }

        button.btn-submit {
            background-color: #007bff;
            color: #fff;
            font-size: 1.1rem;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button.btn-submit:hover {
            background-color: #0056b3;
        }

        .payment-method h5 {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .payment-method .form-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        input[type="radio"] {
            transform: scale(1.2);
        }

        select,
        input[type="radio"] {
            margin-top: 8px;
        }

        /* Shopping Cart */
        .shopping-cart_container {
            flex: 1;
        }

        .shopping-cart_container table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .shopping-cart_container table th,
        .shopping-cart_container table td {
            padding: 8px;
            text-align: left;
        }

        .total-shopping-cart {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            text-align: right;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .checkout__shipping-address {
                padding: 15px;
            }

            .checkout__shipping-form {
                gap: 12px;
            }

            .form-group {
                flex-direction: column;
                align-items: flex-start;
            }

            label {
                width: 100%;
            }

            input[type="text"],
            input[type="email"],
            input[type="tel"],
            select {
                width: 100%;
            }
        }
    </style>

    <div class="checkout-container" style="display: flex; gap: 20px;">
        <div class="checkout__shipping-address" style="flex: 1;">
            <div class="checkout__shipping-header">
                <h5>Shipping Information</h5>
            </div>

            <form id="checkoutForm" action="/submit-form" method="POST" class="checkout__shipping-form">
                <fieldset class="checkout__shipping-form">
                    <div class="form-group">
                        <label for="firstName">First Name*</label>
                        <input type="text" id="firstName" name="firstName" placeholder="First Name" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name*</label>
                        <input type="text" id="lastName" name="lastName" placeholder="Last Name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address*</label>
                        <input type="email" id="email" name="email" placeholder="Email Address" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number*</label>
                        <input type="tel" id="phone" name="phone" placeholder="Phone Number" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Full Address*</label>
                        <input type="text" id="address" name="address" placeholder="Full Address" required>
                    </div>
                    <div class="form-group">
                        <label for="city">City*</label>
                        <input type="text" id="city" name="city" placeholder="City" required>
                    </div>
                    <div class="form-group">
                        <label for="zip">Postal Code*</label>
                        <input type="text" id="zip" name="zip" placeholder="Postal Code" required>
                    </div>
                    <div class="form-group">
                        <label for="country">Country/Region</label>
                        <select id="country" name="country" required>
                            <option value="ES">Spain</option>
                        </select>
                    </div>

                    <!-- Total amount (readonly) -->
                    <div class="form-group">
                        <label for="total">Total Amount*</label>
                        <input type="text" id="total" name="total" value="{{ number_format($cart['total_price'], 2) }}" readonly>
                    </div>

                    <button type="button" class="btn-submit" id="showModalBtn">Review & Pay</button>
                </fieldset>
            </form>
        </div>

        <div class="shopping-cart_container" style="flex: 1;">
            <h1 class="checkout__shipping-header">Product List</h1>
            @if(!empty($cart_items))
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($cart_items as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ number_format($item['price'], 2) }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="total-shopping-cart">
                    <strong>Total: {{ number_format($cart['total_price'], 2) }}</strong>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div id="checkoutModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModalBtn">&times;</span>
            <div class="modal-header">Review Your Order</div>
            <div class="modal-body">
                <h4>Customer Details</h4>
                <p><strong>First Name:</strong> <span id="modalFirstName"></span></p>
                <p><strong>Last Name:</strong> <span id="modalLastName"></span></p>
                <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                <p><strong>Phone:</strong> <span id="modalPhone"></span></p>
                <p><strong>Address:</strong> <span id="modalAddress"></span></p>
                <p><strong>City:</strong> <span id="modalCity"></span></p>
                <p><strong>Postal Code:</strong> <span id="modalZip"></span></p>
                <p><strong>Country/Region:</strong> <span id="modalCountry"></span></p>
                <p><strong>Total Amount:</strong> $<span id="modalTotalAmount"></span></p>

                <!-- Payment Information -->
                <h4>Payment Information</h4>
                <div class="form-group">
                    <label for="cardNumber">Card Number*</label>
                    <input type="text" id="cardNumber" name="cardNumber" placeholder="Card Number" required>
                </div>
                <div class="form-group">
                    <label for="expirationDate">Expiration Date*</label>
                    <input type="text" id="expirationDate" name="expirationDate" placeholder="MM/YY" required>
                </div>
                <div class="form-group">
                    <label for="cvv">CVV*</label>
                    <input type="text" id="cvv" name="cvv" placeholder="CVV" required>
                </div>
            </div>
            <div class="modal-footer">
                <button id="submitOrderBtn" class="btn-submit">Submit Order</button>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('showModalBtn').addEventListener('click', function () {
            const form = document.getElementById('checkoutForm');
            const inputs = form.querySelectorAll('input[required], select[required]');
            let allFilled = true;

            inputs.forEach(input => {
                if (!input.value) {
                    allFilled = false;
                }
            });

            if (allFilled) {
                // Display modal content
                document.getElementById('modalFirstName').textContent = document.getElementById('firstName').value;
                document.getElementById('modalLastName').textContent = document.getElementById('lastName').value;
                document.getElementById('modalEmail').textContent = document.getElementById('email').value;
                document.getElementById('modalPhone').textContent = document.getElementById('phone').value;
                document.getElementById('modalAddress').textContent = document.getElementById('address').value;
                document.getElementById('modalCity').textContent = document.getElementById('city').value;
                document.getElementById('modalZip').textContent = document.getElementById('zip').value;
                document.getElementById('modalCountry').textContent = document.getElementById('country').value;
                document.getElementById('modalTotalAmount').textContent = document.getElementById('total').value;

                // Show the modal
                document.getElementById('checkoutModal').style.display = 'block';
            } else {
                alert('Please fill out all required fields.');
            }
        });

        document.getElementById('closeModalBtn').addEventListener('click', function () {
            document.getElementById('checkoutModal').style.display = 'none';
        });

        document.getElementById('submitOrderBtn').addEventListener('click', function () {
            // Logic for order submission (e.g., send data to the server)
            alert('Order submitted successfully!');
            document.getElementById('checkoutModal').style.display = 'none';
        });

        window.onclick = function (event) {
            if (event.target === document.getElementById('checkoutModal')) {
                document.getElementById('checkoutModal').style.display = 'none';
            }
        };
    </script>
@endsection

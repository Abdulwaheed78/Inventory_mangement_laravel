<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Purchase Invoice</title>
</head>

<body style="font-family: Arial, sans-serif; margin: 0; padding: 0;">
    <div style="width: 100%; margin: 0 auto;">
        <!-- Invoice Title and Logo in Table Format -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="text-transform: uppercase; font-size: 24px; font-weight: bold; padding: 10px;">
                   Purchase Invoice From IMS Abdul
                </td>
                {{-- <td style="text-align: right; padding: 10px;">
                    <img src="{{ asset('admin/assets/img/ims.png') }}" alt="Logo" style="max-height: 50px; display: block;">
                </td> --}}

            </tr>
        </table>


        <!-- FROM & TO in Table Format -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; border: 0px solid #ddd;">
            <tr>
                <td style="width: 50%; padding: 10px; border-right: 0px solid #ddd; vertical-align: top;">
                    <h4 style="margin-bottom: 5px;">From</h4>
                    <p style="margin: 0;"><strong>IMS Abdul</strong></p>
                    <p style="margin: 0;">402 Shanti Nagar</p>
                    <p style="margin: 0;">Wadala East Mumbai, </p>
                    <p style="margin: 0;">Maharastra, 400037 India.</p>
                    <p style="margin: 0;">Phone: 7718929730</p>
                    <p style="margin: 0;">Email: awc361@gmail.com</p>
                </td>
                <td style="width: 50%; padding: 10px; vertical-align: top;">
                    <h4 style="margin-bottom: 5px;">Bill To</h4>
                    <p style="margin: 0;"><strong>{{ $order->supplier->name }}</strong></p>
                    <p style="margin: 0;">{{ $order->supplier->address }}</p>
                    <p style="margin: 0;">Phone: {{ $order->supplier->phone }}</p>
                    <p style="margin: 0;">Email: {{ $order->supplier->email }}</p>
                </td>
            </tr>
        </table>

        <!-- Invoice Details in Table Format -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom:20px;">
            <tr>
                <td style="padding: 5px; "><strong>Invoice #:</strong></td>
                <td style="padding: 5px; ">{{ '#000' . $order->id }}</td>
            </tr>
            <tr>
                <td style="padding:  5px; "><strong>Order ID:</strong></td>
                <td style="padding:  5px;  ">{{ '#000' . $order->id }}</td>
            </tr>
            <tr>
                <td style="padding:  5px; "><strong>Invoice Date:</strong></td>
                <td style="padding:  5px;  ">{{ date('d M Y', strtotime($order->order_date)) }}</td>
            </tr>
            <tr>
                <td style="padding:  5px; "><strong>Payment:</strong></td>
                <td style="padding:  5px; ">{{ $order->status }}</td>
            </tr>

        </table>

        <!-- Table -->
        <div style="width: 100%; overflow-x: auto; border: 1px solid #ddd;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Qty</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Product</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Unit Price</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $subtotal = 0;
                    ?>
                    @foreach ($order->trans as $index => $trans)
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $trans->qty }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $trans->products->name }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">{{ $trans->price }}
                            </td>
                            <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">
                                {{ $trans->qty * $trans->price }}</td>
                        </tr>
                        <?php
                        $subtotal += $trans->price * $trans->qty;
                        ?>
                    @endforeach
                    <tr>
                        <td colspan="3" style="border: 1px solid #ddd; padding: 8px; text-align: right;">
                            <strong>Subtotal</strong>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">Rs {{$subtotal}}</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border: 1px solid #ddd; padding: 8px; text-align: right;">
                            <strong>Shipping</strong>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">Free</td>
                    </tr>
                    <tr style="background-color: #f2f2f2;">
                        <td colspan="3" style="border: 1px solid #ddd; padding: 8px; text-align: right;">
                            <strong>Total</strong>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"><strong>Rs {{$subtotal}}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>

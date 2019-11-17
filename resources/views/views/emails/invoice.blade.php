<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Detail Pemesanan Barang</title>

        <style>
            .invoice-box{
                max-width:800px;
                margin:auto;
                padding:30px;
                border:1px solid #eee;
                box-shadow:0 0 10px rgba(0, 0, 0, .15);
                font-size:16px;
                line-height:24px;
                font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
                color:#555;
            }

            .invoice-box table{
                width:100%;
                line-height:inherit;
                text-align:left;
            }

            .invoice-box table td{
                padding:5px;
                vertical-align:top;
            }

            .invoice-box table tr td:nth-child(2){
                text-align:right;
            }

            .invoice-box table tr.top table td{
                padding-bottom:20px;
            }

            .invoice-box table tr.top table td.title{
                font-size:45px;
                line-height:45px;
                color:#333;
            }

            .invoice-box table tr.information table td{
                padding-bottom:40px;
            }

            .invoice-box table tr.heading td{
                background:#eee;
                border-bottom:1px solid #ddd;
                font-weight:bold;
            }

            .invoice-box table tr.details td{
                padding-bottom:20px;
            }

            .invoice-box table tr.item td{
                border-bottom:1px solid #eee;
            }

            .invoice-box table tr.item.last td{
                border-bottom:none;
            }

            .invoice-box table tr.total td:nth-child(2){
                border-top:2px solid #eee;
                font-weight:bold;
            }

            @media only screen and (max-width: 600px) {
                .invoice-box table tr.top table td{
                    width:100%;
                    display:block;
                    text-align:center;
                }

                .invoice-box table tr.information table td{
                    width:100%;
                    display:block;
                    text-align:center;
                }
            }
        </style>
    </head>

    <body>
        <div class="invoice-box" style="max-width: 800px;margin: auto;padding: 30px;border: 1px solid #eee;box-shadow: 0 0 10px rgba(0, 0, 0, .15);font-size: 16px;line-height: 24px;font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;color: #555;">
            <table cellpadding="0" cellspacing="0" style="width: 100%;line-height: inherit;text-align: left;">
                <tr class="top">
                    <td colspan="4" style="padding: 5px;vertical-align: top;">
                        <table style="width: 100%;line-height: inherit;text-align: left;">
                            <tr>
                                <td style="width: 65%;padding: 5px;vertical-align: top;padding-bottom: 5px;">
                                    No. Order : {{$dataInvoice[0]->invoice_number}}<br>
                                    Payment Information : {{$payment[0]->description}}<br>
                                    Payment Status : {{$status[0]->description}}
                                </td>
                                
                                
                                <td style="width: 35%;padding: 5px;vertical-align: top;text-align: left;padding-bottom: 5px;">
                                    Purchase Date : {{$dataInvoice[0]->created_date}}<br>
                                    Total Purchase : <?= 'Rp. ' . strrev(implode('.', str_split(strrev(strval($dataInvoice[0]->grand_total)), 3))) ?>
                                </td>
                            </tr>
                        </table>
                        <hr>
                    </td>
                </tr>


                <tr class="information">
                    <td colspan="2" style="padding: 5px;vertical-align: top;">
                        <table style="width: 100%;line-height: inherit;text-align: left;">
                            <tr>

                                <td class="title" style="padding: 5px;vertical-align: top;padding-bottom: 20px;font-size: 45px;line-height: 45px;color: #333;">
                                    <img src="{{asset('images/product/'.$detailProduct->big_photo)}}" style="width:100%;height: 100%;max-height: 100px; max-width:130px;">
                                </td>

                                <td style="padding: 5px;vertical-align: top;text-align: left;padding-bottom: 20px;">
                                    	
{{$detailProduct->product_name}} <br>
Name: {{$dataInvoice[0]->name}}<br>
Pengiriman : {{$dataInvoice[0]->address."-".$dataInvoice[0]->city_name}}<br>
No.Handphone :  {{$dataInvoice[0]->no_hp}}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr class="heading">
                    <td style="width: 65%;padding: 5px;vertical-align: top;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">
                        Detail Purchase 
                    </td>
                    <td style="width: 15%;padding: 5px;vertical-align: top;text-align: left;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">
                       
                    </td>
                    <td style=" width: 5%;padding: 5px;vertical-align: top;text-align: left;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">
                        
                    </td>
                    <td style="width: 15%;padding: 5px;vertical-align: top;text-align: left;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">
                       
                    </td>
                </tr>

                

                <tr class="heading">
                    <td style="width: 65%;padding: 5px;vertical-align: top;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">
                        Order Information
                    </td>
                    <td style="width: 15%;padding: 5px;vertical-align: top;text-align: center;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">
                        Kuantitas
                    </td>
                    <td style=" width: 5%;padding: 5px;vertical-align: top;text-align: left;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">
                        
                    </td>
                    <td style="width: 15%;padding: 5px;vertical-align: top;text-align: left;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">
                        Total
                    </td>
                </tr>
                @foreach($dataInvoiceLine as $line)
                <tr class="item">
                    <td style="width: 65%;padding: 5px;vertical-align: top;background: #eee;border-bottom: 1px solid #ddd;">
                        {{$line->name}}
                    </td>
                    <td style="width: 15%;padding: 5px;vertical-align: top;text-align: center;background: #eee;border-bottom: 1px solid #ddd;">
                        {{$line->qty}}
                    </td>
                    <td style=" width: 5%;padding: 5px;vertical-align: top;text-align: left;background: #eee;border-bottom: 1px solid #ddd;">
                        Rp.
                    </td>
                    <td style="width: 15%;padding: 5px;vertical-align: top;text-align: left;background: #eee;border-bottom: 1px solid #ddd;">
                       {{$line->total_cost}}
                    </td>
                </tr>

                @endforeach

                <tr class="total">
                    <td style="width: 65%;padding: 5px;vertical-align: top;background: #eee;border-bottom: 1px solid #ddd;">
                        
                    </td>
                    <td style="width: 15%;padding: 5px;vertical-align: top;text-align: center;background: #eee;border-bottom: 1px solid #ddd;">
                        Total
                    </td>
                    <td style=" width: 5%;padding: 5px;vertical-align: top;text-align: left;background: #eee;border-bottom: 1px solid #ddd;">
                        Rp.
                    </td>
                    <td style="width: 15%;padding: 5px;vertical-align: top;text-align: left;background: #eee;border-bottom: 1px solid #ddd;">
                       {{$dataInvoice[0]->grand_total}}
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
    <tbody>
        <tr>
            <td align="center" valign="top">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color:#ffffff;border:1px solid #dadada;border-radius:3px!important">
                    <tbody>
                        <tr>
                            <td>
                                <div style="padding:15px 20px;background:#f6f9ff;padding-bottom:15px">
                                    <table style="background:#f6f9ff;font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;font-size:14px;width:100%">
                                        <tbody>
                                            <tr>
                                                <td>
                                                  
                                                    <h1 style="color:#000000;font-size:30px;line-height:100%">Order Status Updated</h1>
                                                </td>
                                               
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    <tr>
                        <td align="center" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="600">
                                <tbody>
                                <tr>
                                    <td valign="top" style="background-color:#ffffff;padding:px px 0">
                                        <table border="0" cellpadding="20" cellspacing="0" width="100%" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif">
                                            <tbody>
                                            <tr>
                                                <td valign="top" style="padding-bottom:0px">
  
                                                    <div style="color:#636363;font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left;margin-top:30px">
                                                        <h4 style="font-weight:600;font-size:18px">Hi {{$name}},</h4>
                                                        <p style="margin:0 0 16px;font-size:14px;line-height:26px;color:#000000;text-align:left">
                                                           Your order <b> #{{config('global.order_prefix').date(date('Ymd', strtotime($order->created_at))).$order->order_id}}</b> status updated to {{$ord_status}}</p>
                                                    </div>
                                                </td>
                                                
                                            </tr>
                                          
                                            <tr>
                                                <td>
                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td style="width:50%;background:#f6f9ff;padding:15px 10px;font-size:14px">
                                                                    <h4>ORDER SUMMARY</h4>
                                                                    <table style="font-size:14px;width:100%">
                                                                        <tbody><tr><td><table style="font-size:14px;width:100%">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td style="padding:5px">Invoice No:</td>
                                                                                <td style="padding:5px">#{{$order->invoice_id}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="padding:5px">Sub Total:</td>
                                                                                <td style="padding:5px">AED {{$order->total}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="padding:5px">Tax:</td>
                                                                                <td style="padding:5px">AED {{$order->vat}}</td>
                                                                            </tr>
                                                                           
                                                                            
                                                                                                                                                            <tr>
                                                                                <td style="padding:5px">Discount:</td>
                                                                                <td style="padding:5px">AED {{$order->discount}}</td>
                                                                            </tr>
                                                                                                                                                             
                                                                            <tr>
                                                                                <td style="padding:5px">Grand Total:</td>
                                                                                <td style="padding:5px">AED {{$order->grand_total}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="padding:5px">Payment :</td>
                                                                                <td style="padding:5px">
                                                                                   @if($order->payment_mode==1)
                                                                                   Wallet
                                                                                    @endif  
                                                                                    @if($order->payment_mode==2)
                                                                                    Card
                                                                                     @endif  
                                                                                     @if($order->payment_mode==3)
                                                                                     Apple Pay
                                                                                      @endif                                                                                 </td>
                                                                                
                                                                            
                                                                        </tr></tbody>
                                                                    </table>
                                                                </td>
                                                                <td style="width:50%;background:#f6f9ff;padding:15px 10px;font-size:14px">
                                                                    <h4>Delivery Address</h4>
                                                                    <p style="font-weight:700;margin-bottom:0px">{{$order->address->full_name}}</p>
                                                                    <p style="margin-top:5px;line-height:22px">
                                                                        {{$order->address->building}} <br>
  
                                                                        {{$order->address->apartment}}<br>
                                                                        {{$order->address->street}}<br>
                                                                        {{$order->address->location}}<br>
                                                                        {{$order->address->land_mark}}<br>
  
                                                                    </p>
                                                                </td>
                                                            </tr></tbody></table></td></tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td>
                                                    <table style="font-size:14px;width:100%;border-top:1px solid #dadada;border-bottom:1px solid #dadada">
                                                        <tbody>
                                                            @foreach ($order->products as $prd)
                                                            <tr>
  
                                                                <td style="width:100px">
                                                                    <img style="max-width:100px;padding-right:5px" src="{{$prd->image}}" alt="" >
                                                                </td>
                                                                <td>
                                                                    <div style="float:left">
                                                                        <p>{{$prd->product_name}}</p>
                                                                        <p></p>
                                                                        <p></p>
                                                                        <p></p>
                                                                        <p>Quantity : {{$prd->quantity}}</p>
  
                                                                        
                                                                    </div>
                                                                    
                                                                </td>
  
                                                                <td>
                                                                    <div style="float:left">
                                                                        <p>Price : AED {{$prd->price}}</p>
                                                                       
                                                                    </div>
                                                                    
                                                                </td>
  
  
                                                                <td>
                                                                    <div style="float:left">
                                                                        <p>Discount : AED {{$prd->discount}}</p>
                                                                       
                                                                    </div>
                                                                    
                                                                </td>
  
                                                                <td>
                                                                    <div style="float:left">
                                                                        <p>Total : AED {{$prd->total}}</p>
                                                                    </div>
                                                                    
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                                                                                             
                                                                                                                    </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h4 style="color:#000000;font-size:14px;margin:0px 0px 8px;text-align:left;font-weight:700">Much love,</h4>
                                                    <p style="color:#000000;font-size:16px;margin:0px 0px 10px;text-align:left;font-weight:700">HOP</p>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                   
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" valign="top">
  
            <table border="0" cellpadding="10" cellspacing="0" width="600">
                <tbody>
                <tr>
                <td align="center" valign="top">
                    <table border="0" cellpadding="10" cellspacing="0" width="600">
                        <tbody>
                        <tr>
                            <td valign="top" style="padding:0">
                                <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                    <tbody>
                                    <tr>
                                        <td colspan="2" valign="middle" style="padding:0;border:0;color:#aac482;font-family:Arial;font-size:12px;line-height:125%;text-align:center">
                                            <p style="color:#ffffff;padding-top:20px;margin-top:0px">
                                                © 2023 HOP. All Rights Reserved.</p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                        </td></tr>
                        </tbody>
                    </table>
                </td>
                </tr>
            </tbody>
        </table>
  
        </td></tr>
    </tbody>
  </table>
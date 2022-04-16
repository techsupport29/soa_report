{{-- @component('mail::message') --}}
<div style="font-family:Courier New; font-size:16px;">
    Good day <strong>Ma'am/Sir</strong>,
        <div class="container" style=" text-indent: 50px;">

            Attached here with is the  <span  style="background-color: #ffff00; color: #ff6600; font-weight:bold"> "Statement of Account" </span> for the event held on <strong>{{$date}} </strong>
        Please be advised that remittances should be deposited to the company on a daily basis except
        on weekends and holidays,unless thru online banking. Also, upon deposit, kindly send us a clear
        copy of the deposit slip. Thank you and God bless!
        </div><br>




    <span>---</span><br>
    Best Regards, <br>
    Finance <br>
    Lucky 8 Star Quest Inc. <br>
</div>


{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

{{-- {{ config('app.name') }} --}}
{{-- @endcomponent --}}

{{-- @component('mail::message') --}}
<div style="font-family:Courier New; font-size:16px;">
    Good day <strong>Ma'am/Sir</strong>,
    <br>
    <br>
        <div class="container" style=" text-indent: 50px;">
            Attached here with is the <span  style="background-color: #ffff00; color: #ff6600; font-weight:bold">  "for Replenishment Report"  </span>
        for the event held on  <strong>{{$date}} </strong>.Please be advised that replenishments
        are only available during banking days.
        Thank you and God bless!
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

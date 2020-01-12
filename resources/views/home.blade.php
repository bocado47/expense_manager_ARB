@extends('layouts.app')

@section('loggedcontent')

<br />
<div class="container">
    <div class="card">
        <div class="card-body">
            <div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h2>My Expenses</h2>
            <table class="table">
                <tr>
                    <th style="text-align:center;">Expenses Categories</th>
                    <th style="text-align:center;">Total</th>
                </tr>
                @foreach ($final as $value)
                    <tr>
                        <td style="text-align:center;">{{$value['name']}}</td>
                        <td style="text-align:center;">{{$value['y']}}</td>
                    </tr>
                @endforeach
            </table>
        </diV>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    var array={!! json_encode($final) !!}
    // var new_array={}
    var new_array=[]
    $.each( array, function( key, value ) {
        new_array.push(value);
    });
    console.log(new_array);
    Highcharts.chart('container', {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  title: {
    text: 'Expenses'
  },
  tooltip: {
    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: true,
        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
      }
    }
  },
  series: [{
    name: 'Brands',
    colorByPoint: true,
    data: new_array
  }]
});
});
</script>
@endsection

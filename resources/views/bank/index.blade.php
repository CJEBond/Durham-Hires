@extends('layouts.app')

@section('content')
<div class="treasurer">

@if ($success == 1)
<div class="alert alert-success">
  <strong>Success!</strong> Thank you - the payment is correct.
</div>
@elseif ($success == 2)
<div class="alert alert-warning">
  <strong>Warning!</strong> Something doesn't match-up. Please check and try again.
</div>
@elseif ($success == 3)
<div class="alert alert-warning">
  <strong>Warning!</strong> The referance doesn't match-up. Please check and try again.
</div>
@endif

  {!! Form::Open(
  array(
    'route' => 'bank.submit',
    'class' => 'form')
  ) !!}
  {{ Form::Hidden('attempt', $attempt)}}

    @if (count($errors) > 0)
    <div class="alert alert-danger">
        There were some problems adding the item.<br />
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

  <div class="form-group form-inline">
    {{ Form::label('ref', 'Bank Referance: ') }}
    {{ Form::text('ref', $ref,
      array(
        'class' => 'form-control'
      )
    ) }}
  </div>

  <div class='form-group form-inline'>
      {{ Form::label('amount', 'Amount paid: £') }}
    {{ Form::text('amount', $amount,
      array(
        'class' => 'form-control moneyInput'
      )
    ) }}
  </div>

  <div class="form-group">
    {{ Form::submit('Submit',
      array('class' => 'btn btn-primary')
    )}}
  </div>

  {{ Form::close() }}
</div>
@endsection

@section('scripts')
<script>
function bindMoney(){
      $('.moneyInput').change(function() {
         var num = parseFloat($(this).val()); // get the current value of the input field.
         $(this).val(num.toFixed(2));
      });
}
    window.onload = bindMoney();
</script>
@endsection

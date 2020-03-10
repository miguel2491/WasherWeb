@extends('layouts.admin')

@section('main-title')
Inicio
@endsection
@section('main-content')
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<div class="row">
						<div class="col-md-7">
							<h5><i class="glyphicon glyphicon-align-justify"></i> Stripe</h5>
						</div>
						<div class="col-md-5">
							<div class="pull-right">
								
							</div>
						</div>
					</div>
				</div>
				<div class="ibox-content">
					<!-- Inicia Cuerpo  de la Vista -->
                    <h1>TEST BOOK</h1>
                    <h3>$10.00</h3>
                    <form action="/make-payment" method="POST">
                        {{ csrf_field() }}
                        <script
                            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                            data-key="{{ config('services.stripe.key') }}"
                            data-amount="1000"
                            data-name="Demo Book"
                            data-description="This is good start up book."
                            data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                            data-locale="auto">
                        </script>
                    </form>
					<!-- Fin Cuerpo de la Vista -->
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
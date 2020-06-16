
@extends('layouts.home')

@section('main')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Calender</div>

                <div class="panel-body">
                    <div id="calendar-wrap">
			    		<header>
			    			<h1>{{ $month_year }}</h1>
			    		</header>
			    		<?php echo $month_dates; ?>

			    	</div><!-- /. wrap -->

                </div>
            </div>
        </div>
    </div>
@endsection
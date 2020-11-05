<ul class="list-group">
  	<li class="list-group-item d-flex justify-content-between align-items-center">
    	<a href="{{ route('customer.orders') }}" class="list-group-item-action">
		Orders
		</a>
   	<order-status-badge :customer="{{Auth::guard('customer')->user()}}"></order-status-badge>
  	</li>
  	<li class="list-group-item d-flex justify-content-between align-items-center">
   	<a href="{{ route('customer.replacements') }}" class="list-group-item-action">Replacements</a>
   	{{-- <replacement-status-badge :customer="{{Auth::guard('customer')->user()}}"></replacement-status-badge> --}}
  	</li>
  	<li class="list-group-item d-flex justify-content-between align-items-center">
   	<a href="{{ route('customer_address') }}" class="list-group-item-action">Addresses</a>
  	</li>
  	<li class="list-group-item d-flex justify-content-between align-items-center">
   	<a href="{{ route('customer.account_details') }}" class="list-group-item-action">Account Details</a>
  	</li>
</ul>
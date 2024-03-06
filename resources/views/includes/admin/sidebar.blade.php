<nav class="sidebar sidebar-offcanvas" id="sidebar">
	<ul class="nav">
	  @if((Auth::user()->roles->contains('5') && in_array('dashboard',$staffPermissions)) || Auth::user()->roles->contains('1'))
		<li class="nav-item">
			<a class="nav-link" href="{{ route('admin.dashboard') }}">
				<i class="mdi mdi-grid-large menu-icon"></i>
				<span class="menu-title">Dashboard</span>
			</a>
		</li>
		@endif

		@can('user_management_access')
		@if((Auth::user()->roles->contains('5') && in_array('user_management',$staffPermissions)) || Auth::user()->roles->contains('1'))
		<li class="nav-item {{ ((request()->is('admin/users*')) || (request()->is('admin/permissions*')) || (request()->is('admin/roles*'))) ? 'active' : '' }}">
			<a class="nav-link" data-bs-toggle="collapse" href="#user" aria-expanded="{{ ((request()->is('admin/users*')) || (request()->is('admin/permissions*')) || (request()->is('admin/roles*'))) ? 'true' : 'false' }}" aria-controls="user">
				<i class="menu-icon mdi mdi-account-circle-outline"></i>
				<span class="menu-title">Users Management</span>
				<i class="menu-arrow"></i>
			</a>
			<div class="collapse {{ ((request()->is('admin/users*')) || (request()->is('admin/permissions*')) || (request()->is('admin/roles*'))) ? 'show' : '' }}" id="user">
				<ul class="nav flex-column sub-menu">
				@can('user_access')
				@if((Auth::user()->roles->contains('5') && in_array('users',$staffPermissions)) || Auth::user()->roles->contains('1'))
				<li class="nav-item"> <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"> Users </a></li>
				@endif
				@endcan
				<!-- @can('permission_access')
				@if((Auth::user()->roles->contains('5') && in_array('permissions',$staffPermissions)) || Auth::user()->roles->contains('1'))
				<li class="nav-item"> <a class="nav-link {{ request()->is('admin/permissions*') ? 'active' : '' }}" href="{{ route('admin.permissions.index') }}"> Permissions </a></li>
				@endif
				@endcan
				@can('role_access')
				@if((Auth::user()->roles->contains('5') && in_array('roles',$staffPermissions)) || Auth::user()->roles->contains('1'))
				<li class="nav-item"> <a class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}"> Roles </a></li>
				@endif
				@endcan -->
				<li class="nav-item"> <a class="nav-link {{ request()->is('admin/user-referals*') ? 'active' : '' }}" href="{{ route('admin.user-referal') }}"> User Referals </a></li>
				</ul>
			</div>
		</li>
		@endif
		@endcan

		@can('page_access')
		@if((Auth::user()->roles->contains('5') && in_array('pages',$staffPermissions)) || Auth::user()->roles->contains('1'))
		<li class="nav-item {{ request()->is('admin/pages*') ? 'active' : '' }}">
			<a class="nav-link" href="{{ route('admin.pages.index') }}">
				<i class="mdi mdi-book-open-page-variant menu-icon"></i>
				<span class="menu-title">Pages</span>
			</a>
		</li>
		@endif
		@endcan

		@can('slider_access')
		@if((Auth::user()->roles->contains('5') && in_array('slider',$staffPermissions)) || Auth::user()->roles->contains('1'))
		<li class="nav-item {{ request()->is('admin/sliders*') ? 'active' : '' }}">
			<a class="nav-link" href="{{ route('admin.sliders.index') }}">
				<i class="mdi mdi-image-area menu-icon"></i>
				<span class="menu-title">Homepage Slider</span>
			</a>
		</li>
		@endif
		@endcan

		@can('category_access')
		@if((Auth::user()->roles->contains('5') && in_array('categories',$staffPermissions)) || Auth::user()->roles->contains('1'))
		<li class="nav-item {{ request()->is('admin/categories*') ? 'active' : '' }}">
			<a class="nav-link" href="{{ route('admin.categories.index') }}">
				<i class="mdi mdi-format-list-bulleted menu-icon"></i>
				<span class="menu-title">Categories</span>
			</a>
		</li>
		@endif
		@endcan

		@can('product_access')
		@if((Auth::user()->roles->contains('5') && in_array('products',$staffPermissions)) || Auth::user()->roles->contains('1'))
		<li class="nav-item {{ request()->is('admin/admin-products*') ? 'active' : '' }}">
			<a class="nav-link" href="{{ route('admin.admin-products.index') }}">
				<i class="mdi mdi-cart-outline menu-icon"></i>
				<span class="menu-title">Products</span>
			</a>
		</li>
		@endif
		@endcan

		@can('store_product_access')
		@if((Auth::user()->roles->contains('5') && in_array('store_products',$staffPermissions)) || Auth::user()->roles->contains('1'))
		<li class="nav-item {{ request()->is('admin/vendor-products*') ? 'active' : '' }}">
			<a class="nav-link" href="{{ route('admin.vendor-products.index') }}">
				<i class="mdi mdi-store menu-icon"></i>
				<span class="menu-title">Store Products</span>
			</a>
		</li>
		@endif
		@endcan

		@can('faq_access')
		@if((Auth::user()->roles->contains('5') && in_array('faq',$staffPermissions)) || Auth::user()->roles->contains('1'))
		<li class="nav-item {{ request()->is('admin/faqs*') ? 'active' : '' }}">
			<a class="nav-link" href="{{ route('admin.faqs.index') }}">
				<i class="mdi mdi-comment-question-outline menu-icon"></i>
				<span class="menu-title">FAQ's</span>
			</a>
		</li>
		@endif
		@endcan

		@can('email_template_access')
		@if((Auth::user()->roles->contains('5') && in_array('email_templates',$staffPermissions)) || Auth::user()->roles->contains('1'))
		<li class="nav-item {{ request()->is('admin/email-templates*') ? 'active' : '' }}">
			<a class="nav-link" href="{{ route('admin.email-templates.index') }}">
				<i class="mdi mdi-email-outline menu-icon"></i>
				<span class="menu-title">Email Templates</span>
			</a>
		</li>
		@endif
		@endcan
				
		@can('coupon_access')
		@if((Auth::user()->roles->contains('5') && in_array('coupons',$staffPermissions)) || Auth::user()->roles->contains('1'))
		<li class="nav-item {{ request()->is('admin/coupons*') ? 'active' : '' }}">
			<a class="nav-link" href="{{ route('admin.coupons.index') }}">
				<i class="mdi mdi-wallet-giftcard menu-icon"></i>
				<span class="menu-title">Coupons</span>
			</a>
		</li>
		@endif
		@endcan
		
		@can('coupon_inventory_access')
		@if((Auth::user()->roles->contains('5') && in_array('coupon_inventory',$staffPermissions)) || Auth::user()->roles->contains('1'))
		<li class="nav-item {{ request()->is('admin/coupon-inventories*') ? 'active' : '' }}">
			<a class="nav-link" href="{{ route('admin.coupon-inventories.index') }}">
				<i class="mdi mdi-view-headline menu-icon"></i>
				<span class="menu-title">Coupon Inventory</span>
			</a>
		</li>
		@endif
		@endcan
		@if((Auth::user()->roles->contains('5') && in_array('banks',$staffPermissions)) || Auth::user()->roles->contains('1'))
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="collapse" href="#bank" aria-expanded="false" aria-controls="bank">
				<i class="mdi mdi-bank menu-icon"></i>
				<span class="menu-title">Banks</span>
				<i class="menu-arrow"></i>
			</a>
			<div class="collapse" id="bank">
				<ul class="nav flex-column sub-menu">
				<li class="nav-item"> <a class="nav-link" href="{{ route('admin.banks.index') }}"> Bank's List </a></li>
				</ul>
			</div>
		</li>
         @endif
		@can('order_access')
		@if((Auth::user()->roles->contains('5') && in_array('orders',$staffPermissions)) || Auth::user()->roles->contains('1'))
		<li class="nav-item {{ request()->is('admin/orders*') ? 'active' : '' }}">
			<a class="nav-link" href="{{ route('admin.orders.index') }}">
				<i class="mdi mdi-receipt menu-icon"></i>
				<span class="menu-title">Orders</span>
			</a>
		</li>
		@endif
		@endcan

		@can('wallet_transaction_access')
		@if((Auth::user()->roles->contains('5') && in_array('wallet_transactions',$staffPermissions)) || Auth::user()->roles->contains('1'))
		<li class="nav-item {{ request()->is('admin/wallet-transactions*') ? 'active' : '' }}">
			<a class="nav-link" href="{{ route('admin.wallet-transactions.index') }}">
				<i class="mdi mdi-wallet menu-icon"></i>
				<span class="menu-title">Wallet Transaction</span>
			</a>
		</li>
		@endif
		@endcan

		@can('admin_commission_access')
		<li class="nav-item {{ request()->is('admin/admin-commissions*') ? 'active' : '' }}">
			<a class="nav-link" href="{{ route('admin.admin-commissions') }}">
				<i class="mdi mdi-account-tie menu-icon"></i>
				<span class="menu-title">Admin Commission</span>
			</a>
		</li>
		@endcan

		@can('tax_commission_access')
		<li class="nav-item {{ request()->is('admin/tax-commissions*') ? 'active' : '' }}">
			<a class="nav-link" href="{{ route('admin.tax-commissions') }}">
				<i class="mdi mdi-calculator-variant menu-icon"></i>
				<span class="menu-title">Tax Commission</span>
			</a>
		</li>
		@endcan

		@can('withdrawal_request_access')
		@if((Auth::user()->roles->contains('5') && in_array('withdrawal_requests',$staffPermissions)) || Auth::user()->roles->contains('1'))
		<li class="nav-item {{ request()->is('admin/withdrawal-requests*') ? 'active' : '' }}">
			<a class="nav-link" href="{{ route('admin.withdrawal-requests.index') }}">
				<i class="mdi mdi-currency-inr menu-icon"></i>
				<span class="menu-title">Withdrawal Requests</span>
			</a>
		</li>
		@endif
		@endcan

		@can('tax_access')
		@if((Auth::user()->roles->contains('5') && in_array('tax',$staffPermissions)) || Auth::user()->roles->contains('1'))
		<li class="nav-item {{ request()->is('admin/taxes*') ? 'active' : '' }}">
			<a class="nav-link" href="{{ route('admin.taxes.index') }}">
				<i class="mdi mdi-calculator menu-icon"></i>
				<span class="menu-title">Tax</span>
			</a>
		</li>
		@endif
		@endcan

		@can('setting_access')
		@if((Auth::user()->roles->contains('5') && in_array('setting_management',$staffPermissions)) || Auth::user()->roles->contains('1'))
		<li class="nav-item {{ ((request()->is('admin/site-setting*')) || (request()->is('admin/app-setting*')) || (request()->is('admin/notifications*'))) ? 'active' : '' }}">
			<a class="nav-link" data-bs-toggle="collapse" href="#setting" aria-expanded="{{ ((request()->is('admin/site-setting*')) || (request()->is('admin/app-setting*')) || (request()->is('admin/notifications*'))) ? 'true' : 'false' }}" aria-controls="setting">
				<i class="mdi mdi-settings menu-icon"></i>
				<span class="menu-title">Settings</span>
				<i class="menu-arrow"></i>
			</a>
			<div class="collapse {{ ((request()->is('admin/site-setting*')) || (request()->is('admin/app-setting*')) || (request()->is('admin/notifications*'))) ? 'show' : '' }}" id="setting">
				<ul class="nav flex-column sub-menu">
				@if((Auth::user()->roles->contains('5') && in_array('site_setting',$staffPermissions)) || Auth::user()->roles->contains('1'))
					<li class="nav-item">
						<a class="nav-link {{ request()->is('admin/site-setting*') ? 'active' : '' }}" href="{{ route('admin.site-setting.index') }}"> Site Setting </a>
					</li>
               @endif
			   @if((Auth::user()->roles->contains('5') && in_array('app_setting',$staffPermissions)) || Auth::user()->roles->contains('1'))
					<li class="nav-item">
						<a class="nav-link {{ request()->is('admin/app-setting*') ? 'active' : '' }}" href="{{ route('admin.app-setting.index') }}"> App Setting </a>
					</li>
                @endif
				@if((Auth::user()->roles->contains('5') && in_array('notification',$staffPermissions)) || Auth::user()->roles->contains('1'))
					<li class="nav-item">
						<a class="nav-link {{ request()->is('admin/notifications*') ? 'active' : '' }}" href="{{ route('admin.notifications.index') }}">Notification </a>
					</li>
				@endif
				</ul>
			</div>
		</li>
		@endif
		@endcan

	</ul>
</nav>
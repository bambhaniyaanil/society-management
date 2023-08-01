<nav class="sidebar">
    <div class="sidebar-header">
        <a href="#" class="sidebar-brand">
            <img src="{{ URL::asset('assets/images/logo.png')}}" class="img-fluid" style="width: 170px;" />
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <!-- <li class="nav-item nav-category">Main</li> -->
            @if(in_array(1, $permissions))
            <li class="nav-item {{ active_class(['/']) }}">
                <a href="{{ url('/') }}" class="nav-link">
                    <img src="{{ URL::asset('assets/icons/dashboard.png') }}" width="20">
                    <span class="link-title">Dashboard</span>
                </a>
            </li>
            @endif
            @if(in_array(2, $permissions))
            <li class="nav-item {{ active_class(['users']) }}">
                <a href="{{ url('users') }}" class="nav-link" aria-expanded="{{ is_active_route(['users/*']) }}">
                    <img src="{{ URL::asset('assets/icons/avatar.png') }}" width="20">
                    <span class="link-title">User</span>
                </a>
            </li>
            @endif
            <!-- <li class="nav-item {{ active_class(['society']) }}">
        <a href="{{ url('society') }}" class="nav-link" aria-expanded="{{ is_active_route(['society/*']) }}">
          <i class="link-icon" data-feather="box"></i>
          <span class="link-title">Society</span>
        </a>
      </li> -->
            @if(in_array(6, $permissions))
            <li class="nav-item {{ active_class(['flat']) }}">
                <a href="{{ url('flat') }}" class="nav-link" aria-expanded="{{ is_active_route(['flat/*']) }}">
                    <img src="{{ URL::asset('assets/icons/flat.png') }}" width="20">
                    <span class="link-title">Flats</span>
                </a>
            </li>
            @endif
            @if(in_array(9, $permissions))
            <li class="nav-item {{ active_class(['ledger']) }}">
                <a href="{{ url('ledger') }}" class="nav-link" aria-expanded="{{ is_active_route(['ledger/*']) }}">
                    <img src="{{ URL::asset('assets/icons/ledger.png') }}" width="20">
                    <span class="link-title">Ledger Creation</span>
                </a>
            </li>
            @endif
            @if(in_array(15, $permissions))
            <li class="nav-item {{ active_class(['payment-voucher']) }}">
                <a href="{{ url('payment-voucher') }}" class="nav-link"
                    aria-expanded="{{ is_active_route(['payment-voucher/*']) }}">
                    <img src="{{ URL::asset('assets/icons/voucher.png') }}" width="20">
                    <span class="link-title">Payment Voucher</span>
                </a>
            </li>
            @endif
            @if(in_array(21, $permissions))
            <li class="nav-item {{ active_class(['receipts-voucher']) }}">
                <a href="{{ url('receipts-voucher') }}" class="nav-link"
                    aria-expanded="{{ is_active_route(['receipts-voucher/*']) }}">
                    <img src="{{ URL::asset('assets/icons/bill.png') }}" width="20">
                    <span class="link-title">Receipts Voucher</span>
                </a>
            </li>
            @endif
            @if(in_array(31, $permissions))
            <li class="nav-item {{ active_class(['journal-voucher']) }}">
                <a href="{{ url('journal-voucher') }}" class="nav-link"
                    aria-expanded="{{ is_active_route(['journal-voucher/*']) }}">
                    <img src="{{ URL::asset('assets/icons/journal.png') }}" width="20">
                    <span class="link-title">Journal Voucher</span>
                </a>
            </li>
            @endif
            @if(in_array(37, $permissions))
            <li class="nav-item {{ active_class(['group-type']) }}">
                <a href="{{ url('group-type') }}" class="nav-link"
                    aria-expanded="{{ is_active_route(['group-type/*']) }}">
                    <img src="{{ URL::asset('assets/icons/team.png') }}" width="20">
                    <span class="link-title">Group Type</span>
                </a>
            </li>
            @endif
            @if(in_array(41, $permissions))
            <li class="nav-item {{ active_class(['group-category']) }}">
                <a href="{{ url('group-category') }}" class="nav-link"
                    aria-expanded="{{ is_active_route(['group-category/*']) }}">
                    <img src="{{ URL::asset('assets/icons/group.png') }}" width="20">
                    <span class="link-title">Group Category</span>
                </a>
            </li>
            @endif
            @if(in_array(45, $permissions))
            <li class="nav-item {{ active_class(['group-creations']) }}">
                <a href="{{ url('group-creations') }}" class="nav-link"
                    aria-expanded="{{ is_active_route(['group-creations/*']) }}">
                    <img src="{{ URL::asset('assets/icons/add-group.png') }}" width="20">
                    <span class="link-title">Group Creations</span>
                </a>
            </li>
            @endif
            @if(in_array(49, $permissions))
            <li class="nav-item {{ active_class(['invoice']) }}">
                <a href="{{ url('invoice') }}" class="nav-link" aria-expanded="{{ is_active_route(['invoice/*']) }}">
                    <img src="{{ URL::asset('assets/icons/invoice.png') }}" width="20">
                    <span class="link-title">Invoice</span>
                </a>
            </li>
            @endif
            @if(in_array(79, $permissions))
            <li class="nav-item {{ active_class(['parking-owner']) }}">
                <a href="{{ url('parking-owner') }}" class="nav-link"
                    aria-expanded="{{ is_active_route(['parking-owner/*']) }}">
                    <img src="{{ URL::asset('assets/icons/parking.png') }}" width="20">
                    <span class="link-title">Parking Owner Details</span>
                </a>
            </li>
            @endif
            @if(in_array(83, $permissions))
            <li class="nav-item {{ active_class(['parking-register']) }}">
                <a href="{{ url('parking-register') }}" class="nav-link"
                    aria-expanded="{{ is_active_route(['parking-register/*']) }}">
                    <img src="{{ URL::asset('assets/icons/parking (1).png') }}" width="20">
                    <span class="link-title">Parking Register</span>
                </a>
            </li>
            @endif
            @if(in_array(87, $permissions))
            <li class="nav-item {{ active_class(['tenant-register']) }}">
                <a href="{{ url('tenant-register') }}" class="nav-link"
                    aria-expanded="{{ is_active_route(['tenant-register/*']) }}">
                    <img src="{{ URL::asset('assets/icons/owner.png') }}" width="20">
                    <span class="link-title">Tenant Register</span>
                </a>
            </li>
            @endif
            @if(in_array(59, $permissions))
            <li class="nav-item {{ active_class(['reports/*']) }}">
                <a class="nav-link" data-toggle="collapse" href="#reports" role="button"
                    aria-expanded="{{ is_active_route(['reports/*']) }}" aria-controls="reports">
                    <img src="{{ URL::asset('assets/icons/report.png') }}" width="20">
                    <span class="link-title">Reports</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class(['reports/*']) }}" id="reports">
                    <ul class="nav sub-menu">
                        @if(in_array(60, $permissions))
                        <li class="nav-item">
                            <a href="{{ url('/reports/ledger') }}"
                                class="nav-link {{ active_class(['reports/ledger']) }}">Ledger</a>
                        </li>
                        @endif
                        @if(in_array(62, $permissions))
                        <li class="nav-item">
                            <a href="{{ url('/reports/profit-loss') }}"
                                class="nav-link {{ active_class(['reports/profit-loss']) }}">Profit & Loss</a>
                        </li>
                        @endif
                        @if(in_array(64, $permissions))
                        <li class="nav-item">
                            <a href="{{ url('/reports/balance-sheet') }}"
                                class="nav-link {{ active_class(['reports/balance-sheet']) }}">Balance Sheet</a>
                        </li>
                        @endif
                        @if(in_array(66, $permissions))
                        <li class="nav-item">
                            <a href="{{ url('/reports/closing-balance') }}"
                                class="nav-link {{ active_class(['reports/closing-balance']) }}">Closing Balance</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif
            @if(in_array(68, $permissions))
            <li class="nav-item {{ active_class(['mail-integrations']) }}">
                <a href="{{ url('mail-integrations') }}" class="nav-link"
                    aria-expanded="{{ is_active_route(['mail-integrations/*']) }}">
                    <img src="{{ URL::asset('assets/icons/mail-inbox-app.png') }}" width="20">
                    <span class="link-title">Mail Integrations</span>
                </a>
            </li>
            @endif
            @if(in_array(72, $permissions))
            <li class="nav-item {{ active_class(['role-permission']) }}">
                <a href="{{ url('role-permission') }}" class="nav-link"
                    aria-expanded="{{ is_active_route(['role-permission/*']) }}">
                    <img src="{{ URL::asset('assets/icons/login.png') }}" width="20">
                    <span class="link-title">Role Permission</span>
                </a>
            </li>
            @endif
            @if(in_array(91, $permissions))
            <li class="nav-item {{ active_class(['email-notification']) }}">
                <a href="{{ url('email-notification') }}" class="nav-link"
                    aria-expanded="{{ is_active_route(['email-notification/*']) }}">
                    <img src="{{ URL::asset('assets/icons/mail-inbox-app.png') }}" width="20">
                    <span class="link-title">Email Notification</span>
                </a>
            </li>
            @endif
            <li class="nav-item {{ active_class(['receipt-form-list']) }}">
                <a href="{{ url('receipt-form-list') }}" class="nav-link"
                    aria-expanded="{{ is_active_route(['receipt-form-list/*']) }}">
                    <img src="{{ URL::asset('assets/icons/mail-inbox-app.png') }}" width="20">
                    <span class="link-title">Receipt Form</span>
                </a>
            </li>

            <li class="nav-item {{ active_class(['payment-form-list']) }}">
                <a href="{{ url('payment-form-list') }}" class="nav-link"
                    aria-expanded="{{ is_active_route(['payment-form-list/*']) }}">
                    <img src="{{ URL::asset('assets/icons/voucher.png') }}" width="20">
                    <span class="link-title">Payment Form</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
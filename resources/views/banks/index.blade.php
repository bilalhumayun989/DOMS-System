@extends('layouts.app')
@php $pageTitle = 'Banks'; @endphp

@section('content')
<div x-data="{ addBankOpen: false, transactionOpen: false }" @keydown.escape.window="addBankOpen = false; transactionOpen = false">
<div class="mb-5 flex flex-wrap items-end justify-between gap-4">
    <div>
        <p class="text-xs font-bold uppercase tracking-widest text-blue-600">AAA Traders</p>
        <h2 class="mt-1 text-2xl font-black text-slate-900">Bank Detail &amp; Ledger Sheet</h2>
        <p class="mt-1 text-sm text-slate-500">Seeded client view for bank transactions, account balances, and audit-ready controls.</p>
    </div>
        <div class="flex gap-2"><button type="button" @click="addBankOpen = true" class="rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-700">+ Add Bank</button><button type="button" @click="transactionOpen = true" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-bold text-white">+ New Transaction</button></div>
</div>
<div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach([['Total Combined Bank Balance',$combinedBalance,'#1d4ed8','#eff6ff','All bank and cash accounts'],['Total Cash in Hand',$cashBalance,'#15803d','#f0fdf4','Physical cash account'],['Total Monthly Deposits',$totalDeposits,'#0369a1','#f0f9ff','Credits recorded this month'],['Total Monthly Withdrawals',$totalWithdrawals,'#c2410c','#fff7ed','Debits recorded this month']] as [$label,$value,$color,$background,$description])
    <div class="min-h-32 rounded-xl border border-slate-200 p-5 shadow-sm" style="background:{{ $background }}"><div class="flex items-start justify-between gap-3"><p class="text-xs font-bold uppercase tracking-wide" style="color:{{ $color }};opacity:.75">{{ $label }}</p><span class="h-2.5 w-2.5 flex-shrink-0 rounded-full" style="background:{{ $color }}"></span></div><p class="mt-2 text-2xl font-black" style="color:{{ $color }}">{{ pkr($value) }}</p><p class="mt-1 text-xs font-medium text-slate-500">{{ $description }}</p></div>
    @endforeach
</div>

<div class="mb-5 grid grid-cols-1 gap-5 xl:grid-cols-3">
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white xl:col-span-2">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-5">
            <div><h3 class="text-lg font-black text-slate-900">Tab 1: Bank Transactions</h3><p class="mt-1 text-xs text-slate-500">Data entry log with dynamic running balance.</p></div>
            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-600">Excel Table: BankTransactions</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[68rem] text-sm">
                <thead><tr class="bg-slate-50 text-left text-xs font-bold uppercase tracking-wide text-slate-400"><th class="px-5 py-3">Date</th><th class="px-5 py-3">Bank Name</th><th class="px-5 py-3">Account No. / IBAN</th><th class="px-5 py-3">Transaction Type</th><th class="px-5 py-3">Category</th><th class="px-5 py-3">Reference / Cheque No.</th><th class="px-5 py-3">Description / Notes</th><th class="px-5 py-3 text-right">Amount (PKR)</th><th class="px-5 py-3 text-right">Running Balance</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                @php $runningBalance = 0; @endphp
                @foreach($transactions as $transaction)
                    @php $runningBalance += $transaction['type'] === 'Deposit / Credit' ? $transaction['amount'] : -$transaction['amount']; @endphp
                    <tr class="cursor-pointer transition-colors duration-200 hover:bg-blue-50" tabindex="0" role="link" @click="window.location.href = '{{ route('banks.show', $transaction['bank_id']) }}'" @keydown.enter="window.location.href = '{{ route('banks.show', $transaction['bank_id']) }}'"><td class="px-5 py-3.5 whitespace-nowrap">{{ $transaction['date'] }}</td><td class="px-5 py-3.5 font-semibold text-blue-700">{{ $transaction['bank'] }}</td><td class="px-5 py-3.5 font-mono text-xs text-slate-500">{{ $transaction['account'] }}</td><td class="px-5 py-3.5"><span class="rounded-full px-2 py-1 text-xs font-bold {{ $transaction['type'] === 'Deposit / Credit' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">{{ $transaction['type'] }}</span></td><td class="px-5 py-3.5 text-slate-600">{{ $transaction['category'] }}</td><td class="px-5 py-3.5 font-mono text-xs">{{ $transaction['reference'] }}</td><td class="px-5 py-3.5 text-slate-600">{{ $transaction['description'] }}</td><td class="px-5 py-3.5 text-right font-bold">{{ pkr($transaction['amount']) }}</td><td class="px-5 py-3.5 text-right font-black {{ $runningBalance >= 0 ? 'text-blue-700' : 'text-red-600' }}">{{ pkr($runningBalance) }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white">
        <div class="border-b border-slate-100 px-6 py-5"><h3 class="text-lg font-black text-slate-900">Tab 2: Balance Dashboard &amp; Summary</h3><p class="mt-1 text-xs text-slate-500">Account-level position and last activity.</p></div>
        <div class="overflow-x-auto"><table class="w-full min-w-[36rem] text-sm"><thead><tr class="bg-slate-50 text-left text-xs font-bold uppercase tracking-wide text-slate-400"><th class="px-5 py-3">Bank Account</th><th class="px-5 py-3">Account Number</th><th class="px-5 py-3 text-right">Opening Balance</th><th class="px-5 py-3 text-right">Current Balance</th><th class="px-5 py-3">Last Updated</th></tr></thead><tbody class="divide-y divide-slate-100">@foreach($accounts as $account)<tr class="cursor-pointer transition-colors duration-200 hover:bg-blue-50" tabindex="0" role="link" @click="window.location.href = '{{ route('banks.show', $account['id']) }}'" @keydown.enter="window.location.href = '{{ route('banks.show', $account['id']) }}'"><td class="px-5 py-3.5 font-semibold"><a href="{{ route('banks.show', $account['id']) }}" class="block text-blue-700">{{ $account['bank'] }}</a></td><td class="px-5 py-3.5 font-mono text-xs text-slate-500"><a href="{{ route('banks.show', $account['id']) }}">{{ $account['account'] }}</a></td><td class="px-5 py-3.5 text-right">{{ pkr($account['opening']) }}</td><td class="px-5 py-3.5 text-right font-black text-blue-700">{{ pkr($account['current']) }}</td><td class="px-5 py-3.5 text-xs text-slate-500">{{ $account['updated'] }}</td></tr>@endforeach</tbody></table></div>
    </section>
</div>

<section class="rounded-2xl border border-slate-200 bg-white p-6">
    <div class="mb-5"><h3 class="text-lg font-black text-slate-900">Automation &amp; Maintenance Rules</h3><p class="mt-1 text-sm text-slate-500">Use these exact structures in Excel or Google Sheets for a maintainable audit trail.</p></div>
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        <div class="rounded-xl bg-slate-50 p-5"><h4 class="font-black text-slate-800">Bank Transactions columns</h4><ol class="mt-3 list-decimal space-y-1 pl-5 text-sm text-slate-600"><li>Date (DD-MMM-YYYY)</li><li>Bank Name</li><li>Account No. / IBAN</li><li>Transaction Type</li><li>Category</li><li>Reference / Cheque No.</li><li>Description / Notes</li><li>Amount (PKR / Currency)</li><li>Running Balance</li></ol><p class="mt-4 text-xs text-slate-500">Select the range and press <strong>Ctrl + T</strong>. Name the table <strong>BankTransactions</strong>; new rows inherit formulas automatically.</p></div>
        <div class="rounded-xl bg-blue-50 p-5"><h4 class="font-black text-slate-800">Running balance formulas</h4><p class="mt-3 text-xs font-bold text-slate-500">Excel Table formula in I2:</p><code class="mt-1 block overflow-x-auto rounded bg-white p-3 text-xs text-blue-800">=SUMIFS(BankTransactions[Amount],BankTransactions[Bank Name],[@[Bank Name]],BankTransactions[Transaction Type],"Deposit / Credit")-SUMIFS(BankTransactions[Amount],BankTransactions[Bank Name],[@[Bank Name]],BankTransactions[Transaction Type],"Withdrawal / Debit")</code><p class="mt-3 text-xs font-bold text-slate-500">Single-account running balance in I2, copied down:</p><code class="mt-1 block overflow-x-auto rounded bg-white p-3 text-xs text-blue-800">=SUMIFS($H$2:H2,$D$2:D2,"Deposit / Credit")-SUMIFS($H$2:H2,$D$2:D2,"Withdrawal / Debit")</code></div>
        <div class="rounded-xl bg-emerald-50 p-5"><h4 class="font-black text-slate-800">Dashboard KPI formulas</h4><div class="mt-3 space-y-2 text-xs text-slate-700"><p><strong>Total Combined Bank Balance:</strong> <code>=SUM(BankSummary[Current Balance])</code></p><p><strong>Total Cash in Hand:</strong> <code>=SUMIFS(BankSummary[Current Balance],BankSummary[Bank Account],"Cash in Hand")</code></p><p><strong>Total Monthly Deposits:</strong> <code>=SUMIFS(BankTransactions[Amount],BankTransactions[Transaction Type],"Deposit / Credit",BankTransactions[Date],">="&amp;EOMONTH(TODAY(),-1)+1,BankTransactions[Date],"<="&amp;EOMONTH(TODAY(),0))</code></p><p><strong>Total Monthly Withdrawals:</strong> <code>=SUMIFS(BankTransactions[Amount],BankTransactions[Transaction Type],"Withdrawal / Debit",BankTransactions[Date],">="&amp;EOMONTH(TODAY(),-1)+1,BankTransactions[Date],"<="&amp;EOMONTH(TODAY(),0))</code></p></div></div>
        <div class="rounded-xl bg-amber-50 p-5"><h4 class="font-black text-slate-800">Data validation &amp; formatting</h4><div class="mt-3 space-y-2 text-sm text-slate-600"><p><strong>Bank Name:</strong> List: Bank A, Bank B, Cash in Hand.</p><p><strong>Transaction Type:</strong> List: Deposit / Credit, Withdrawal / Debit.</p><p><strong>Category:</strong> List: Primary Lifting, Retail Collection, Wholesale Collection, Pending Claim, Expenses, Transfer.</p><p><strong>Date:</strong> Date between 01-Jan-2020 and 31-Dec-2100; display as <strong>dd-mmm-yyyy</strong>.</p><p><strong>Amount and balances:</strong> Number format <strong>#,##0.00</strong>; use conditional formatting for debits red and credits green.</p><p><strong>Audit controls:</strong> Freeze the header row, protect formula column I, and keep references unique.</p></div></div>
    </div>
</section>

<div x-show="transactionOpen" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" @click.self="transactionOpen = false">
    <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl">
        <div class="flex items-center justify-between"><div><h3 class="text-lg font-black text-slate-900">New Bank Transaction</h3><p class="mt-1 text-xs text-slate-500">Enter one deposit or withdrawal in the AAA Traders ledger.</p></div><button type="button" @click="transactionOpen = false" class="text-xl text-slate-400">&times;</button></div>
        <form class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2" @submit.prevent="transactionOpen = false">
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Date *</label><input type="date" required class="w-full rounded-lg border border-slate-300 bg-white text-sm"></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Bank Name *</label><select required class="w-full rounded-lg border border-slate-300 bg-white text-sm"><option>Bank A</option><option>Bank B</option><option>Cash in Hand</option></select></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Account No. / IBAN</label><input placeholder="PK00 AAAA 0001" class="w-full rounded-lg border border-slate-300 bg-white text-sm"></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Transaction Type *</label><select required class="w-full rounded-lg border border-slate-300 bg-white text-sm"><option>Deposit / Credit</option><option>Withdrawal / Debit</option></select></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Category *</label><select required class="w-full rounded-lg border border-slate-300 bg-white text-sm"><option>Primary Lifting</option><option>Retail Collection</option><option>Wholesale Collection</option><option>Pending Claim</option><option>Expenses</option><option>Transfer</option></select></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Reference / Cheque No.</label><input placeholder="e.g. DEP-260903" class="w-full rounded-lg border border-slate-300 bg-white text-sm"></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Amount (PKR) *</label><input type="number" min="0.01" step="0.01" required placeholder="0.00" class="w-full rounded-lg border border-slate-300 bg-white text-sm"></div>
            <div class="md:col-span-2"><label class="mb-1 block text-xs font-bold text-slate-600">Description / Notes</label><textarea rows="3" placeholder="Transaction details for audit trail" class="w-full rounded-lg border border-slate-300 bg-white text-sm"></textarea></div>
            <div class="flex justify-end gap-2 md:col-span-2"><button type="button" @click="transactionOpen = false" class="rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-600">Cancel</button><button class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-bold text-white">Save Transaction</button></div>
        </form>
    </div>
</div>

<div x-show="addBankOpen" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4" @click.self="addBankOpen = false">
    <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl">
        <div class="flex items-center justify-between"><div><h3 class="text-lg font-black text-slate-900">Add Bank Account</h3><p class="mt-1 text-xs text-slate-500">Create a new AAA Traders account record. This is a dummy client form.</p></div><button type="button" @click="addBankOpen = false" class="text-xl text-slate-400">&times;</button></div>
        <form class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2" @submit.prevent="addBankOpen = false">
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Bank Name *</label><select required class="w-full rounded-lg border border-slate-300 bg-white text-sm"><option>Bank A</option><option>Bank B</option><option>Cash in Hand</option><option>Other Bank</option></select></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Account Type *</label><select required class="w-full rounded-lg border border-slate-300 bg-white text-sm"><option>Business Current</option><option>Business Savings</option><option>Cash Account</option></select></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Account No. / IBAN *</label><input required placeholder="PK00 AAAA 0003" class="w-full rounded-lg border border-slate-300 bg-white text-sm"></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Branch / Location *</label><input required placeholder="Main Boulevard Branch" class="w-full rounded-lg border border-slate-300 bg-white text-sm"></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Opening Balance (PKR) *</label><input type="number" min="0" step="0.01" required placeholder="0.00" class="w-full rounded-lg border border-slate-300 bg-white text-sm"></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Opening Date *</label><input type="date" required class="w-full rounded-lg border border-slate-300 bg-white text-sm"></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Currency</label><input value="PKR" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 text-sm"></div>
            <div><label class="mb-1 block text-xs font-bold text-slate-600">Account Status</label><select class="w-full rounded-lg border border-slate-300 bg-white text-sm"><option>Active</option><option>Inactive</option></select></div>
            <div class="md:col-span-2"><label class="mb-1 block text-xs font-bold text-slate-600">Notes</label><textarea rows="3" placeholder="Account purpose or audit notes" class="w-full rounded-lg border border-slate-300 bg-white text-sm"></textarea></div>
            <div class="flex justify-end gap-2 md:col-span-2"><button type="button" @click="addBankOpen = false" class="rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-600">Cancel</button><button class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-bold text-white">Add Bank Account</button></div>
        </form>
    </div>
</div>
</div>
@endsection

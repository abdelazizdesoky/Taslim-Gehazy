@extends('Dashboard.layouts.master')

@section('css')
<style>
    .serial-item {
        display: flex;
        align-items: center;
        padding: 5px;
        margin-bottom: 5px;
        border: 1px solid #ddd;
        border-radius: 3px;
        background-color: #f0f8ff;
    }
    .remove-serial-btn {
        background: #f44336;
        color: white;
        border: none;
        border-radius: 3px;
        padding: 0 5px;
        margin-left: 10px;
        cursor: pointer;
    }
    .remove-serial-btn:hover {
        background: #d32f2f;
    }
    #interactive video {
        width: 100%;
        height: auto;
    }
</style>
@endsection

@section('title')
إضافة سيريال
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">الإذن</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">
                <a href="{{ route('employee.invoices.show', $Invoices->id) }}">/ عرض المنتجات</a> / إضافة سيريال
            </span>
        </div>
    </div>
</div>
@endsection

@section('content')
@include('Dashboard.messages_alert')
<div class="col-md-12 col-xl-12 col-xs-12 col-sm-12">
    <div class="card">
        <div class="card-body">
            <p>رقم الإذن: {{ $Invoices->code }}
          -  
                @switch($Invoices->invoice_type)
                    @case(1)
                    استلام
                    @break
                    @case(2)
                    تسليم
                    @break
                    @default
                    مرتجعات
                @endswitch
           - {{ $Invoices->customer->name ?? $Invoices->supplier->name }}</p>
            <P>عدد السيريالات المطلوبة :{{$totalQuantity}}  </P>
            <div id="serialsList" class="mt-3"></div>
            <p>عدد السيريالات: <span id="serialCount">0</span></p>
            
            <div class="form-group">
                <input type="text" class="form-control" id="serialInput" placeholder="أدخل السيريال هنا" autofocus>
                {{-- <button type="button" class="btn btn-primary mt-2" id="startScanner">استخدام الكاميرا</button> --}}
            </div>
            
            <div id="interactive" class="viewport" style="position: relative; width: 100%; height: 300px; display: none;">
                <video autoplay="true" muted="true" playsinline="true"></video>
            </div>
            
            <!-- changed: add id to form so script can target it reliably -->
            <form id="serialsForm" class="form-horizontal" action="{{ route('employee.invoices.store') }}" method="post" autocomplete="off">
                @csrf
                <input type="hidden" name="id" value="{{ $Invoices->id }}">
                <input type="hidden" name="serials" id="serialsHiddenInput">
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success waves-effect waves-light">تأكيد الإذن</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
@endsection

@section('js')
{{-- <script src="{{ URL::asset('dashboard/js/quagga.min.js') }}"></script> --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const serialInput = document.getElementById('serialInput');
    const serialsList = document.getElementById('serialsList');
    const serialCount = document.getElementById('serialCount');
    const serialsHiddenInput = document.getElementById('serialsHiddenInput');
    const startScannerBtn = document.getElementById('startScanner');
    const interactive = document.getElementById('interactive');

    // target the form by id first to avoid grabbing other forms on the page
    const form = document.getElementById('serialsForm') || document.querySelector('form');

    // Total quantity from the server (passed from the controller)
    const totalQuantity = {{ $totalQuantity }};

    // Products from the invoice
    const invoiceProducts = @json($invoiceProducts);

    // إضافة الستايلات المخصصة
    const additionalStyles = `
        .serial-item {
            display: flex;
            align-items: center;
            padding: 10px;
            margin-bottom: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f0f8ff;
            min-height: 60px;
        }
        
        .serial-item span {
            flex: 1;
            line-height: 1.4;
        }
        
        .product-message {
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    `;

    const styleSheet = document.createElement('style');
    styleSheet.textContent = additionalStyles;
    document.head.appendChild(styleSheet);

function validateSerialForProducts(serial) {
    const patterns = [/^09\/I-/];
    let cleanedSerial = serial;
    for (let pattern of patterns) {
        cleanedSerial = cleanedSerial.replace(pattern, '');
    }
    cleanedSerial = cleanedSerial.replace(/^0+/, '');
    const serialPrefix = cleanedSerial.substring(0, 7);

    // أولوية: vendor_code لو موجود
    let matchedProduct = invoiceProducts.find(product => {
        if (product.vendor_code && product.vendor_code.trim() !== "") {
            return serialPrefix === product.vendor_code;
        }
        return false;
    });

    // لو مفيش vendor_code → fallback على product_code
    if (!matchedProduct) {
        matchedProduct = invoiceProducts.find(product => {
            if (product.product_code && product.product_code.trim() !== "") {
                return serialPrefix === product.product_code;
            }
            return false;
        });
    }

    if (matchedProduct) {
        return { isValid: true, product: matchedProduct };
    }

    // غير صالح
    return { isValid: false, product: null };
}




    async function checkSerialExistsGlobally(serial) {
        try {
            const response = await fetch(`{{ url('/employee/check-serial-exists') }}/${serial}`);
            const data = await response.json();
            return data.exists;
        } catch (error) {
            console.error("Error checking serial:", error);
            return false;
        }
    }

    function updateSerialCount() {
        const serials = serialsList.querySelectorAll('.serial-item').length;
        serialCount.textContent = serials;

        if (serials >= totalQuantity) {
            serialInput.disabled = true;
            serialInput.placeholder = '\u062a\u0645 \u0625\u062f\u062e\u0627\u0644 \u0627\u0644\u0643\u0645\u064a\u0629 \u0627\u0644\u0645\u0637\u0644\u0648\u0628\u0629';
            if (startScannerBtn) startScannerBtn.disabled = true;
            alert(`\u062a\u0645 \u0625\u062f\u062e\u0627\u0644 \u0627\u0644\u0643\u0645\u064a\u0629 \u0627\u0644\u0645\u0637\u0644\u0648\u0628\u0629 (${totalQuantity})`);
        } else {
            serialInput.disabled = false;
            serialInput.placeholder = '\u0623\u062f\u062e\u0644 \u0627\u0644\u0633\u064a\u0631\u064a\u0627\u0644 \u0647\u0646\u0627';
            if (startScannerBtn) startScannerBtn.disabled = false;
        }
    }

    function updateHiddenInput() {
        const serials = Array.from(serialsList.querySelectorAll('.serial-item')).map(item => {
            const serialText = item.querySelector('span strong').textContent;
            return serialText;
        });
        serialsHiddenInput.value = serials.join('\n');
    }

    function isSerialDuplicate(serial) {
        const existingSerials = Array.from(serialsList.querySelectorAll('.serial-item')).map(item => {
            return item.querySelector('span strong').textContent;
        });
        return existingSerials.includes(serial);
    }

    async function createSerialItem(serial) {
        if (isSerialDuplicate(serial)) {
            alert('هذا السيريال مكرر على مستوى الفاتورة!');
            return false;
        }

        const validationResult = validateSerialForProducts(serial);
        if (!validationResult.isValid) {
            alert('هذا السيريال غير مرتبط بمنتجات الفاتورة!');
            return false;
        }

        if (serialsList.querySelectorAll('.serial-item').length >= totalQuantity) {
            alert(`لا يمكن إضافة المزيد. الكمية المطلوبة هي ${totalQuantity}`);
            return false;
        }

        const invoiceType = {{ $Invoices->invoice_type }};
        if (invoiceType === 1) {
            const existsGlobally = await checkSerialExistsGlobally(serial);
            if (existsGlobally) {
                alert('هذا السيريال موجود مسبقًا في قاعدة البيانات!');
                return false;
            }
        }

        const serialItem = document.createElement('div');
        serialItem.className = 'serial-item';

        const serialText = document.createElement('span');
        serialText.innerHTML = `
            <strong>${serial}</strong><br>
            <span class="product-message"> ${validationResult.product.product_name} </span>
        `;
        serialItem.appendChild(serialText);

        const removeButton = document.createElement('button');
        removeButton.textContent = '×';
        removeButton.className = 'remove-serial-btn';
        removeButton.addEventListener('click', function () {
            serialsList.removeChild(serialItem);
            updateSerialCount();
            updateHiddenInput();
        });

        serialItem.appendChild(removeButton);
        serialsList.appendChild(serialItem);

        updateSerialCount();
        updateHiddenInput();
        return true;
    }

form.addEventListener('submit', function(event) {
    const serials = Array.from(serialsList.querySelectorAll('.serial-item')).map(item => {
        return item.querySelector('span strong').textContent;
    });

    if (serials.length !== totalQuantity) {
        event.preventDefault();
        alert(`\u064a\u062c\u0628 \u0625\u062f\u062e\u0627\u0644 ${totalQuantity} \u0633\u064a\u0631\u064a\u0627\u0644. \u062a\u0645 \u0625\u062f\u062e\u0627\u0644 ${serials.length} \u0641\u0642\u0637.`);
        return;
    }

            // Join serials with newlines for plain text format
        serialsHiddenInput.value = serials.join('\n');
        console.log("\ud83d\udce6 Serial Data Ready:", serialsHiddenInput.value);
});

 serialInput.addEventListener('keypress', async function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        const serial = serialInput.value.trim();
        if (serial) {
            const added = await createSerialItem(serial);
            if (added) {
                serialInput.value = '';
            }
        }
    }
});


    // Guard scanner setup in case the button was removed/commented out in the view
    if (startScannerBtn) {
        startScannerBtn.addEventListener('click', function() {
            interactive.style.display = 'block';
            Quagga.init({
                inputStream: {
                    type: "LiveStream",
                    target: interactive,
                    constraints: { width: 640, height: 480, facingMode: "environment" }
                },
                decoder: { readers: ["code_128_reader"] }
            }, function(err) {
                if (err) { console.error(err); return; }
                Quagga.start();
            });

            Quagga.onDetected(function(result) {
                const serial = result.codeResult.code;
                if (serial) {
                    if (createSerialItem(serial)) {
                        Quagga.stop();
                        interactive.style.display = 'none';
                    }
                }
            });
        });
    }

    updateSerialCount();
});
</script>
@endsection
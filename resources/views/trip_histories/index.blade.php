@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Trip History</h1>
    <!-- Handsontable CSS/JS for Excel-like editing -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css">
    <script src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
    <!-- SheetJS for Excel export -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <style>
      #hot {
        width: 100%;
        max-width: 100vw;
        height: 70vh;
        overflow: auto;
      }
      .htStatusLunas {
        background: #d4edda !important;
        color: #155724 !important;
      }
      .htStatusTidakLunas {
        background: #f8d7da !important;
        color: #721c24 !important;
      }
    </style>

    <div class="mb-3">
        <label for="monthFilter" class="form-label">Filter by Bulan/Tahun:</label>
        <select id="monthFilter" class="form-select" style="width:auto;display:inline-block;">
            <option value="">-- Semua --</option>
        </select>
        <button id="export-btn" class="btn btn-success ms-2">Export to Excel</button>
    </div>

    <button id="save-btn" class="btn btn-primary mb-3" disabled>Save Changes</button>
    <div id="hot"></div>
</div>

<script>
const tripData = @json($tripData);
const container = document.getElementById('hot');
const saveBtn = document.getElementById('save-btn');
const exportBtn = document.getElementById('export-btn');
let changedRows = {};
let hasUnsavedChanges = false;

// Helper for Rupiah formatting
function formatRupiah(value) {
  if (value == null || value === '') return '';
  return 'Rp ' + Number(value).toLocaleString('id-ID');
}

// Helper for Indonesian date formatting
function formatTanggalIndo(dateString) {
  if (!dateString) return '';
  const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
  const bulan = [
    'Januari','Februari','Maret','April','Mei','Juni',
    'Juli','Agustus','September','Oktober','November','Desember'
  ];
  const d = new Date(dateString);
  if (isNaN(d)) return dateString;
  return `${hari[d.getDay()]}, ${d.getDate().toString().padStart(2, '0')} ${bulan[d.getMonth()]} ${d.getFullYear()}`;
}

// Add a month-year column for sorting/filtering
tripData.forEach(row => {
  if (row.trip_date) {
    const d = new Date(row.trip_date);
    row.trip_month_year = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
  } else {
    row.trip_month_year = '';
  }
});

// Generate unique month-year options for the filter
const monthYearSet = new Set();
tripData.forEach(row => {
  if (row.trip_month_year) monthYearSet.add(row.trip_month_year);
});
const monthYearOptions = Array.from(monthYearSet).sort();

// Populate the filter dropdown
const monthFilter = document.getElementById('monthFilter');
const bulan = [
  '', 'Januari','Februari','Maret','April','Mei','Juni',
  'Juli','Agustus','September','Oktober','November','Desember'
];
monthYearOptions.forEach(val => {
  const [year, month] = val.split('-');
  const label = bulan[parseInt(month)] + ' ' + year;
  const option = document.createElement('option');
  option.value = val;
  option.textContent = label;
  monthFilter.appendChild(option);
});

let filteredData = tripData.slice();

// Define Handsontable columns and headers based on your requested structure
const colHeaders = [
  'Date', 'Rute', 'Supir', 'Mobil', 'Ongkos', 'Uang Jalan', 'Solar', 'Tol',
  'Bongkar/ Muat/ Retribusi/ Penyebrangan', 'Incentive Driver', 'KERNET', 'SISA', 'Sisa',
  'Tanggal Lunas', 'Status', 'SALES', 'Supir', 'Mobil', 'Clientele', 'Document',
  'Printed?', 'NO INVOICE', 'PAYMENT VOUCHER', 'JOURNAL', 'Struk'
];

const columns = [
  {data: 'trip_date', readOnly: true, renderer: (instance, td, row, col, prop, value) => { td.innerText = formatTanggalIndo(value); td.className = 'htCenter'; return td; }},
  {data: 'route'},
  {data: 'driver'},
  {data: 'truck'},
  {data: 'ongkos', type: 'numeric', renderer: (instance, td, row, col, prop, value) => { td.innerText = formatRupiah(value); td.className = 'htRight'; return td; }},
  {data: 'uang_jalan', type: 'numeric', renderer: (instance, td, row, col, prop, value) => { td.innerText = formatRupiah(value); td.className = 'htRight'; return td; }},
  {data: 'solar', type: 'numeric', renderer: (instance, td, row, col, prop, value) => { td.innerText = formatRupiah(value); td.className = 'htRight'; return td; }},
  {data: 'tol', type: 'numeric', renderer: (instance, td, row, col, prop, value) => { td.innerText = formatRupiah(value); td.className = 'htRight'; return td; }},
  {data: 'bongkar_muat', type: 'numeric', renderer: (instance, td, row, col, prop, value) => { td.innerText = formatRupiah(value); td.className = 'htRight'; return td; }},
  {data: 'incentive_driver', type: 'numeric', renderer: (instance, td, row, col, prop, value) => { td.innerText = formatRupiah(value); td.className = 'htRight'; return td; }},
  {data: 'kernet'},
  // SISA: calculated column
  {
    data: 'sisa',
    readOnly: true,
    renderer: (instance, td, row, col, prop, value) => {
      const ongkos = Number(instance.getDataAtRowProp(row, 'ongkos')) || 0;
      const uang_jalan = Number(instance.getDataAtRowProp(row, 'uang_jalan')) || 0;
      const sisa = ongkos - uang_jalan;
      td.innerText = formatRupiah(sisa);
      td.className = 'htRight';
      return td;
    }
  },
  {data: 'sisa2', type: 'numeric', renderer: (instance, td, row, col, prop, value) => { td.innerText = formatRupiah(value); td.className = 'htRight'; return td; }},
  {data: 'tanggal_lunas', renderer: (instance, td, row, col, prop, value) => { td.innerText = formatTanggalIndo(value); td.className = 'htCenter'; return td; }},
  // Status dropdown with color
  {
    data: 'status',
    type: 'dropdown',
    source: ['LUNAS', 'TIDAK LUNAS'],
    renderer: function(instance, td, row, col, prop, value, cellProperties) {
      td.innerText = value;
      if (value === 'LUNAS') {
        td.className = 'htStatusLunas';
      } else if (value === 'TIDAK LUNAS') {
        td.className = 'htStatusTidakLunas';
      } else {
        td.className = '';
      }
      return td;
    }
  },
  {data: 'sales'},
  {data: 'driver2'},
  {data: 'truck2'},
  {data: 'clientele'},
  {data: 'document'},
  {data: 'printed'},
  {data: 'no_invoice'},
  {data: 'payment_voucher'},
  {data: 'journal'},
  {data: 'struk'}
];

// Filter function
monthFilter.addEventListener('change', function(e) {
  if (hasUnsavedChanges) {
    alert('Please save your changes before changing the filter or page.');
    this.value = ""; // Reset filter
    return false;
  }
  const selected = this.value;
  if (selected === '') {
    filteredData = tripData.slice();
  } else {
    filteredData = tripData.filter(row => row.trip_month_year === selected);
  }
  hot.loadData(filteredData);
});

// Prevent navigation if there are unsaved changes
window.addEventListener('beforeunload', function (e) {
  if (hasUnsavedChanges) {
    e.preventDefault();
    e.returnValue = '';
  }
});

// Prevent page/tab navigation (including SPA navigation)
document.addEventListener('click', function(e) {
  if (hasUnsavedChanges) {
    // Only block if it's a link or a button that would navigate
    let el = e.target;
    while (el && el !== document) {
      if (el.tagName === 'A' && el.href && !el.href.endsWith('#')) {
        alert('Please save your changes before leaving the page.');
        e.preventDefault();
        return false;
      }
      el = el.parentElement;
    }
  }
});

const hot = new Handsontable(container, {
  data: filteredData,
  width: '100%',
  height: '70vh',
  stretchH: 'all',
  colHeaders: colHeaders,
  columns: columns,
  rowHeaders: true,
  columnSorting: true,
  licenseKey: 'non-commercial-and-evaluation'
});

// Track changes and enable the save button
hot.addHook('afterChange', function(changes, source) {
  if (source === 'edit' && changes) {
    changes.forEach(function([rowIdx, prop, oldValue, newValue]) {
      if (oldValue !== newValue) {
        const rowData = hot.getSourceDataAtRow(rowIdx);
        changedRows[rowData.id] = rowData;
        saveBtn.disabled = false;
        hasUnsavedChanges = true;
      }
    });
    hot.render();
  }
});

// Save all changed rows when the button is clicked
saveBtn.addEventListener('click', function() {
  if (Object.keys(changedRows).length === 0) return;
  fetch("{{ route('trip-histories.update-multiple') }}", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": "{{ csrf_token() }}"
    },
    body: JSON.stringify({rows: Object.values(changedRows)})
  })
  .then(res => res.json())
  .then(data => {
    if(data.success){
      changedRows = {};
      saveBtn.disabled = true;
      hasUnsavedChanges = false;
      alert('Changes saved!');
    }
  });
});

// Export to Excel function
exportBtn.addEventListener('click', function() {
  const exportData = filteredData.map(row => ({
    'Date': formatTanggalIndo(row.trip_date),
    'Rute': row.route,
    'Supir': row.driver,
    'Mobil': row.truck,
    'Ongkos': formatRupiah(row.ongkos),
    'Uang Jalan': formatRupiah(row.uang_jalan),
    'Solar': formatRupiah(row.solar),
    'Tol': formatRupiah(row.tol),
    'Bongkar/ Muat/ Retribusi/ Penyebrangan': formatRupiah(row.bongkar_muat),
    'Incentive Driver': formatRupiah(row.incentive_driver),
    'KERNET': row.kernet,
    'SISA': formatRupiah((Number(row.ongkos) || 0) - (Number(row.uang_jalan) || 0)),
    'Sisa': formatRupiah(row.sisa2),
    'Tanggal Lunas': formatTanggalIndo(row.tanggal_lunas),
    'Status': row.status,
    'SALES': row.sales,
    'Supir': row.driver2,
    'Mobil': row.truck2,
    'Clientele': row.clientele,
    'Document': row.document,
    'Printed?': row.printed,
    'NO INVOICE': row.no_invoice,
    'PAYMENT VOUCHER': row.payment_voucher,
    'JOURNAL': row.journal,
    'Struk': row.struk
  }));

  // Add summary row (customize as needed)
  exportData.push({
    'Date': '',
    'Rute': '',
    'Supir': '',
    'Mobil': '',
    'Ongkos': '',
    'Uang Jalan': '',
    'Solar': '',
    'Tol': '',
    'Bongkar/ Muat/ Retribusi/ Penyebrangan': '',
    'Incentive Driver': '',
    'KERNET': '',
    'SISA': '',
    'Sisa': '',
    'Tanggal Lunas': '',
    'Status': '',
    'SALES': '',
    'Supir': '',
    'Mobil': '',
    'Clientele': '',
    'Document': '',
    'Printed?': '',
    'NO INVOICE': '',
    'PAYMENT VOUCHER': '',
    'JOURNAL': '',
    'Struk': ''
  });

  // Create worksheet and workbook
  const ws = XLSX.utils.json_to_sheet(exportData);
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, "Trip History");

  // Determine export file name
  let fileName = "RITASE ALL.xlsx";
  if (monthFilter.value) {
    const [year, month] = monthFilter.value.split('-');
    fileName = `RITASE ${bulan[parseInt(month)].toUpperCase().slice(0,3)} ${year.slice(-2)}.xlsx`;
  }
  XLSX.writeFile(wb, fileName);
});
</script>
@endsection
@extends('layouts.app')

@section('content')
<style>
    /* Theme variables */
    :root {
        --bg-color: #1f2630;
        --text-color: #ebebeb;
        --card-bg: #343a40;
        --table-header-bg: #20262f;
        --primary-color: #3fa0a1;
        --primary-hover: #328b8a;
        --border-color: #495057;
    }
    
    body.light-mode {
        --bg-color: #f9f9f9;
        --text-color: #20262f;
        --card-bg: #fff;
        --table-header-bg: #ddd;
        --border-color: #dee2e6;
    }

    body {
        background-color: var(--bg-color);
        color: var(--text-color);
        transition: all 0.3s ease;
    }

    .card {
        background-color: var(--card-bg);
        border: none;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    /* Table Styles */
    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 1rem;
    }

    thead {
        background-color: var(--table-header-bg);
        position: sticky;
        top: 0;
    }

    th, td {
        padding: 0.75rem 1rem;
        text-align: center;
        border-bottom: 1px solid var(--border-color);
    }

    th {
        font-weight: 600;
        user-select: none;
    }

    tr:hover {
        background-color: rgba(63, 160, 161, 0.1);
        transition: background-color 0.3s ease;
    }

    /* Status badges */
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-block;
    }
    
    .status-published {
        background-color: #28a745;
        color: white;
    }
    
    .status-scheduled {
        background-color: #ffc107;
        color: #212529;
    }
    
    .status-draft {
        background-color: #6c757d;
        color: white;
    }

    /* Buttons */
    .btn {
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.3s ease;
        padding: 0.35rem 0.9rem;
        border: none;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }
    
    .btn-primary:hover {
        background-color: var(--primary-hover);
    }
    
    .btn-outline-secondary {
        border: 1.5px solid var(--primary-color);
        color: var(--primary-color);
        background: transparent;
    }
    
    .btn-outline-secondary:hover {
        background-color: var(--primary-color);
        color: white;
    }
    
    .btn-success {
        background-color: #28a745;
        color: white;
    }
    
    .btn-success:hover {
        background-color: #218838;
    }
    
    .btn-danger {
        background-color: #dc3545;
        color: white;
    }
    
    .btn-danger:hover {
        background-color: #bb2d3b;
    }

    /* Pagination */
    .pagination {
        justify-content: center;
        margin-top: 2rem;
    }

    .page-item .page-link {
        background-color: var(--card-bg);
        color: var(--text-color);
        border: 1px solid var(--primary-color);
        margin: 0 0.15rem;
        transition: all 0.3s ease;
    }

    .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        opacity: 0.7;
    }

    .page-item:not(.active):not(.disabled) .page-link:hover {
        background-color: var(--primary-hover);
        color: white;
    }

    /* Skeleton loader */
    .skeleton-wrapper {
        width: 100%;
        border-radius: 8px;
        background: var(--card-bg);
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        padding: 1rem;
    }
    
    .skeleton-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        animation: pulse 1.5s infinite;
    }
    
    .skeleton-cell {
        background: var(--table-header-bg);
        border-radius: 4px;
    }
    
    .skeleton-avatar {
        width: 50px; height: 50px; border-radius: 50%;
    }
    
    .skeleton-text {
        flex-grow: 1;
        height: 20px;
    }
    
    .skeleton-status {
        width: 100px; height: 20px;
    }
    
    .skeleton-actions {
        width: 80px; height: 20px;
    }
    
    @keyframes pulse {
        0% {opacity: 1;}
        50% {opacity: 0.5;}
        100% {opacity: 1;}
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table-container {
            padding: 0;
        }
        
        table {
            display: block;
        }
        
        th, td {
            padding: 0.5rem;
        }
        
        .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
    }
</style>
<!-- Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="container py-4">
    <div class="mb-3">
    @if ($scheduledCount >= $maxScheduledPerDay)
        <div class="alert alert-warning">
            لقد وصلت إلى الحد الأقصى من المنشورات المجدولة لليوم: {{ $maxScheduledPerDay }}.
        </div>
    @else
        <div class="alert alert-info">
            يمكنك جدولة {{ $maxScheduledPerDay - $scheduledCount }} منشور آخر اليوم.
        </div>
    @endif
</div>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h1 class="mb-3 mb-md-0">المنشورات الخاصة بي</h1>
        
        <div class="d-flex gap-2">
            <button id="theme-toggle" class="btn btn-outline-secondary">
                <span id="theme-label">داكن</span> <i class="fas fa-moon ms-1"></i>
            </button>
            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> إنشاء جديد
            </a>
        </div>
    </div>

    <div class="card mb-4 p-3">
        <div class="row g-3">
            <div class="col-md-6 col-lg-4">
                <input id="search-input" type="text" class="form-control" placeholder="ابحث بالعنوان أو المحتوى...">
            </div>
            <div class="col-md-4 col-lg-3">
                <select id="status-filter" class="form-select">
                    <option value="">كل الحالات</option>
                    <option value="published">منشور</option>
                    <option value="scheduled">مجدول</option>
                    <option value="draft">مسودة</option>
                </select>
            </div>
            <div class="col-md-2 col-lg-2">
                <button id="export-csv" class="btn btn-success w-100">
                    <i class="fas fa-file-csv me-1"></i> CSV
                </button>
            </div>
            <div class="col-md-2 col-lg-2">
                <button id="export-pdf" class="btn btn-danger w-100">
                    <i class="fas fa-file-pdf me-1"></i> PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Skeleton loader -->
    <div id="posts-skeleton" class="skeleton-wrapper">
        @for ($i = 0; $i < 5; $i++)
        <div class="skeleton-row">
            <div class="skeleton-cell skeleton-avatar"></div>
            <div class="skeleton-cell skeleton-text"></div>
            <div class="skeleton-cell skeleton-text"></div>
            <div class="skeleton-cell skeleton-text"></div>
            <div class="skeleton-cell skeleton-status"></div>
            <div class="skeleton-cell skeleton-actions"></div>
        </div>
        @endfor
    </div>

    <!-- Posts Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-container ">
                <table id="posts-table" class="table table-hover mb-0 activity-log-container" style="display:none;">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="20%">العنوان</th>
                            <th width="30%">المحتوى</th>
                            <th width="15%">المنصات</th>
                            <th width="10%">الحالة</th>
                            <th width="20%">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                        <tr>
                            <td>{{ $loop->iteration + (($posts->currentPage() - 1) * $posts->perPage()) }}</td>
                            <td class="editable" contenteditable="false" data-post-id="{{ $post->id }}">
                                {{ $post->title }}
                            </td>
                            <td style="max-width:300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $post->content }}
                            </td>
                            <td>
                                @foreach($post->platforms as $platform)
                                    <span class="badge bg-info text-dark me-1">{{ $platform->name }}</span>
                                @endforeach
                            </td>
                           <td>
    <form action="{{ route('posts.update-status', $post) }}" method="POST" id="status-form-{{ $post->id }}">
        @csrf
        @method('PATCH')

        <select name="status" class="form-select rounded-3 shadow-sm border border-secondary-subtle status-dropdown" data-id="{{ $post->id }}" style="background-color: #f4f6f9; color: #1f2630;">
            <option value="draft" {{ $post->status == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="scheduled" {{ $post->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
            <option value="published" {{ $post->status == 'published' ? 'selected' : '' }}>Published</option>
        </select>

        <input type="hidden" name="scheduled_time" id="scheduled-time-{{ $post->id }}">

        <button type="submit" class="btn btn-sm btn-primary mt-2 d-none" id="save-button-{{ $post->id }}">Save</button>
    </form>

    <!-- Modal -->
    <div class="modal fade" id="scheduleModal-{{ $post->id }}" tabindex="-1" aria-labelledby="scheduleModalLabel-{{ $post->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-4 shadow-lg border-0" style="background-color: #f4f6f9;">
                <form onsubmit="setScheduleTime(event, {{ $post->id }})">
                    <div class="modal-header border-bottom-0" style="background-color: #3fa0a1;">
                        <h5 class="modal-title text-white" id="scheduleModalLabel-{{ $post->id }}">🗓️ اختر وقت النشر</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <input type="text" class="form-control schedule-datetime rounded-3 px-3 py-2" id="picker-{{ $post->id }}" placeholder="اختيار التاريخ والوقت" required style="font-size: 1rem;">
                    </div>
                    <div class="modal-footer justify-content-between border-top-0">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn rounded-pill px-4" style="background-color: #3fa0a1; color: white;">نشر مجدول</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</td>


                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('posts.destroy', $post->id) }}" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

  @if ($posts->lastPage() > 1)
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            {{-- Previous Page Link --}}
            @if ($posts->onFirstPage())
                <li class="page-item disabled"><span class="page-link">السابق</span></li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $posts->previousPageUrl() }}">السابق</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @for ($i = 1; $i <= $posts->lastPage(); $i++)
                <li class="page-item {{ $posts->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $posts->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            {{-- Next Page Link --}}
            @if ($posts->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $posts->nextPageUrl() }}">التالي</a>
                </li>
            @else
                <li class="page-item disabled"><span class="page-link">التالي</span></li>
            @endif
        </ul>
    </nav>
@endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Theme toggle
        const toggleBtn = document.getElementById('theme-toggle');
        const themeLabel = document.getElementById('theme-label');

        if(localStorage.getItem('theme') === 'light') {
            document.body.classList.add('light-mode');
            themeLabel.textContent = 'فاتح';
        } else {
            document.body.classList.add('dark-mode');
            themeLabel.textContent = 'داكن';
        }

        toggleBtn.addEventListener('click', () => {
            document.body.classList.toggle('light-mode');
            document.body.classList.toggle('dark-mode');
            
            const isLight = document.body.classList.contains('light-mode');
            themeLabel.textContent = isLight ? 'فاتح' : 'داكن';
            localStorage.setItem('theme', isLight ? 'light' : 'dark');
        });

        // Show table after load
        setTimeout(() => {
            document.getElementById('posts-skeleton').style.display = 'none';
            document.getElementById('posts-table').style.display = 'table';
        }, 500);

   const searchInput = document.getElementById('search-input');
  const statusFilter = document.getElementById('status-filter');
  const table = document.getElementById('posts-table');
function filterPosts() {
  const query = searchInput.value.trim().toLowerCase();
  const status = statusFilter.value.trim().toLowerCase();
  const rows = table.querySelectorAll('tbody tr');

  rows.forEach(row => {
    // Get post status from the select dropdown in the row
    const statusSelect = row.querySelector('.status-dropdown');
    if (!statusSelect) return;

    const postStatus = statusSelect.value.trim().toLowerCase();

    // Assuming you also want to filter by title/content:
    const title = row.cells[1].textContent.toLowerCase();
    const content = row.cells[2].textContent.toLowerCase();

    const matchesQuery = query ? (title.includes(query) || content.includes(query)) : false;
    const matchesStatus = status ? (postStatus === status) : false;

    let showRow = false;

    if (query && status) {
      showRow = matchesQuery && matchesStatus;
    } else if (query) {
      showRow = matchesQuery;
    } else if (status) {
      showRow = matchesStatus;
    } else {
      showRow = true;
    }

    row.style.display = showRow ? '' : 'none';
  });
}


searchInput.addEventListener('input', filterPosts);
statusFilter.addEventListener('change', filterPosts);

         // Export CSV
        document.getElementById('export-csv').addEventListener('click', () => {
            let csv = 'رقم,العنوان,المحتوى,المنصات,الحالة\n';
            const rows = table.querySelectorAll('tbody tr:not([style*="display: none"])');

            rows.forEach(row => {
                const number = row.cells[0].textContent.trim();
                const title = row.cells[1].textContent.trim().replace(/,/g, '');
                const content = row.cells[2].textContent.trim().replace(/,/g, '');
                const platforms = Array.from(row.cells[3].querySelectorAll('span')).map(s => s.textContent).join('|');
                const status = row.cells[4].textContent.trim();

                csv += `${number},${title},${content},${platforms},${status}\n`;
            });

            const blob = new Blob(["\uFEFF" + csv], {type: 'text/csv;charset=utf-8;'});
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'posts_export_' + new Date().toISOString().slice(0, 10) + '.csv';
            link.click();
            URL.revokeObjectURL(link.href);
        });
 

    // Use jsPDF html method to convert HTML content to PDF
 document.getElementById('export-pdf').addEventListener('click', () => {
    const element = document.querySelector('.activity-log-container');
    if (!element) {
        alert('عنصر تصدير PDF غير موجود');
        return;
    }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({
        orientation: 'portrait',
        unit: 'pt',
        format: 'a4'
    });

    doc.html(element, {
        callback: function (doc) {
            doc.save('activity-log.pdf');
            console.log('PDF export successful!');
        },
        x: 10,
        y: 10,
        margin: [10, 10, 10, 10],
        autoPaging: 'text',
        html2canvas: {
            scale: 1,  // try scale 1 for better quality or reduce if slow
            useCORS: true, // allow cross-origin images if any
            logging: true
        }
    }).catch(err => {
        console.error('Error generating PDF:', err);
        alert('حدث خطأ أثناء إنشاء ملف PDF');
    });
});

        // Editable fields
        document.querySelectorAll('.editable').forEach(el => {
            el.addEventListener('dblclick', () => {
                el.contentEditable = true;
                el.focus();
            });
            
            el.addEventListener('blur', () => {
                el.contentEditable = false;
                // Here you would typically send an AJAX request to save the changes
                console.log('Saving changes for post', el.dataset.postId, ':', el.textContent);
            });
            
            el.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    el.blur();
                }
            });
        });
    });



     function setScheduleTime(event, postId) {
        event.preventDefault();
        const dateTime = document.getElementById(`picker-${postId}`).value;
        document.getElementById(`scheduled-time-${postId}`).value = dateTime;
        bootstrap.Modal.getInstance(document.getElementById(`scheduleModal-${postId}`)).hide();
        document.getElementById(`status-form-${postId}`).submit();
    }

    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll('.schedule-datetime').forEach(el => {
            flatpickr(el, {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today",
                time_24hr: true
            });
        });

        document.querySelectorAll('.status-dropdown').forEach(select => {
            const postId = select.dataset.id;
            const modal = new bootstrap.Modal(document.getElementById(`scheduleModal-${postId}`));
            const saveButton = document.getElementById(`save-button-${postId}`);

            select.addEventListener('change', () => {
                if (select.value === 'scheduled') {
                    modal.show();
                } else {
                    saveButton.classList.remove('d-none');
                }
            });
        });
    });
 </script>
     <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

@endsection
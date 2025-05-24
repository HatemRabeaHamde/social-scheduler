@extends('layouts.app')

@section('content')
<style>
    /* كارد الظل والحركة */
    .card {
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 32px rgba(0,0,0,0.25);
    }

    /* عنوان الكارد مع حركة بسيطة */
    .card-header h2 {
        font-weight: 700;
        letter-spacing: 1.2px;
        animation: fadeInDown 0.8s ease forwards;
    }

    /* انيميشن fadeInDown */
    @keyframes fadeInDown {
        0% {
            opacity: 0;
            transform: translateY(-20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* تنبيه المعلومات */
    .alert-info {
        font-size: 1.1rem;
        border-left: 6px solid #0d6efd;
        animation: slideInLeft 0.7s ease forwards;
    }

    @keyframes slideInLeft {
        0% {
            opacity: 0;
            transform: translateX(-50px);
        }
        100% {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* تنبيه الأخطاء */
    .alert-danger {
        border-left: 6px solid #dc3545;
        animation: shake 0.6s ease;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-8px); }
        50% { transform: translateX(8px); }
        75% { transform: translateX(-8px); }
    }

    /* زر الجدولة */
    button[type="submit"] {
        font-weight: 600;
        border-radius: 50px;
        padding: 10px 28px;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.4);
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    button[type="submit"]:hover {
        background-color: #0b5ed7;
        transform: scale(1.05);
    }

    /* عناصر الفورم */
    input.form-control, textarea.form-control, select.form-select {
        border-radius: 10px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    input.form-control:focus, textarea.form-control:focus, select.form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 8px rgba(13,110,253,0.3);
    }

    /* عدد الأحرف */
    #charCount {
        font-weight: 600;
        color: #0d6efd;
        transition: color 0.3s ease;
    }
    textarea.form-control:focus + .text-muted #charCount {
        color: #0a58ca;
    }
</style>

<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <h2 class="h5 mb-0">جدولة منشور جديد</h2>
    </div>
    <div class="card-body">

        {{-- تنبيه بعدد المنشورات المجدولة والمتبقي --}}
        <div class="alert alert-info">
            لقد قمت بجدولة {{ $scheduledCount }} منشور{{ $scheduledCount == 1 ? '' : 'ات' }} اليوم.
            <br>
            متبقٍ لك {{ $remaining }} منشور{{ $remaining == 1 ? '' : 'ات' }} من أصل {{ $maxScheduledPerDay }}.
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>خطأ!</strong> يرجى تصحيح التالي:
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($remaining > 0)
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            
            <div class="mb-3">
                <label for="title" class="form-label">عنوان المنشور</label>
                <input type="text" class="form-control shadow-sm @error('title') is-invalid @enderror" name="title" id="title" required value="{{ old('title') }}" placeholder="اكتب عنوان المنشور هنا...">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 position-relative">
                <label for="content" class="form-label">المحتوى</label>
                <textarea class="form-control shadow-sm @error('content') is-invalid @enderror" name="content" id="content" rows="5" required placeholder="اكتب محتوى المنشور">{{ old('content') }}</textarea>
                <div class="text-muted mt-1 text-end"><span id="charCount">0</span>/2000 حرف</div>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">صورة (اختياري)</label>
                <input class="form-control shadow-sm @error('image') is-invalid @enderror" type="file" name="git commit -m "Initial commit: added project files, README and LICENSE"
" id="image" accept="image/*">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">المنصات</label>
                <div class="row g-2">
                    @foreach($platforms as $platform)
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input @error('platforms') is-invalid @enderror" type="checkbox" name="platforms[]" 
                                       value="{{ $platform->id }}" id="platform-{{ $platform->id }}" {{ (is_array(old('platforms')) && in_array($platform->id, old('platforms'))) ? 'checked' : '' }}>
                                <label class="form-check-label" for="platform-{{ $platform->id }}">
                                    {{ $platform->name }} <span class="badge bg-secondary">{{ $platform->type }}</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('platforms')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="scheduled_time" class="form-label">وقت الجدولة <span class="text-danger">*</span></label>
                <input type="datetime-local" 
                       class="form-control shadow-sm @error('scheduled_time') is-invalid @enderror" 
                       name="scheduled_time" 
                       id="scheduled_time" 
                       required 
                       value="{{ old('scheduled_time') }}">
                @error('scheduled_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary mt-3 w-100 shadow-sm">جدولة المنشور</button>
        </form>
        @else
            <div class="alert alert-warning mt-4 animate__animated animate__fadeIn">
                لقد وصلت إلى الحد الأقصى للمنشورات المجدولة اليوم.
            </div>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary mt-2 w-100 shadow-sm">العودة إلى القائمة</a>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const content = document.getElementById('content');
        const charCount = document.getElementById('charCount');

        // تحديث عداد الأحرف عند التحميل
        charCount.textContent = content.value.length;

        content.addEventListener('input', function () {
            charCount.textContent = this.value.length;
        });
    });
</script> 
@endpush

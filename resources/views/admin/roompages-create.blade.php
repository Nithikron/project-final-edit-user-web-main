@extends('admin.layouts.app')

@section('title', 'เพิ่มห้องพัก')

@section('content')
    <div class="bg-white p-8 rounded-xl shadow">


        <h1 class="text-3xl font-bold mb-6">➕ เพิ่มห้องพัก</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="store-roompages" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block font-medium mb-1">เลขห้อง</label>
                <input type="text" name="name_room" value="{{ old('number') }}" class="w-full border rounded-lg px-4 py-2"
                    placeholder="ใส่เลขห้อง" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">ประเภทห้อง</label>
                <select name="type" class="w-full border rounded-lg px-4 py-2" required>
                    <option value="">-- เลือกประเภทห้อง --</option>
                    <option value="เดี่ยว" {{ old('type') == 'ห้องเดี่ยว' ? 'selected' : '' }}>
                        ห้องเดี่ยว
                    </option>
                    <option value="คู่" {{ old('type') == 'ห้องคู่' ? 'selected' : '' }}>
                        ห้องคู่
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">ประเภทห้อง</label>
                <select name="facility" class="w-full border rounded-lg px-4 py-2" required>
                    <option value="">-- เลือกประเภทห้อง --</option>
                    <option value="พัดลม" {{ old('type') == 'ห้องพัดลม' ? 'selected' : '' }}>
                        ห้องพัดลม
                    </option>
                    <option value="แอร์" {{ old('type') == 'ห้องแอร์' ? 'selected' : '' }}>
                        ห้องแอร์
                    </option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block font-medium mb-1">ค่าเช่า (บาท/วัน)</label>
                <input type="number" name="price" value="{{ old('price') }}" class="w-full border rounded-lg px-4 py-2"
                    placeholder="ใส่ราคา" required>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                    บันทึก
                </button>

                <a href="/roompages" class="flex-1 bg-gray-300 text-center py-2 rounded-lg hover:bg-gray-400">
                    ยกเลิก
                </a>
            </div>

        </form>

    </div>
@endsection

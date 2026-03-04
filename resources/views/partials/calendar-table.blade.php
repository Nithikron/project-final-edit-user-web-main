<table class="table table-bordered table-calendar mb-0">
    <thead class="table-light">
        <tr>
            <th class="text-center calendar-header-sun">อา.</th>
            <th class="text-center calendar-header">จ.</th>
            <th class="text-center calendar-header">อ.</th>
            <th class="text-center calendar-header">พ.</th>
            <th class="text-center calendar-header">พฤ.</th>
            <th class="text-center calendar-header">ศ.</th>
            <th class="text-center calendar-header-sat">ส.</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($calendar['weeks']))
            @foreach ($calendar['weeks'] as $week)
                <tr>
                    @foreach ($week as $day)
                        @if ($day['empty'])
                            <td class="calendar-cell empty"></td>
                        @else
                            @php
                                $isAvailable = $day['available'];
                                $isToday = $day['today'];
                                $isWeekend = $day['weekend'];
                            @endphp
                            <td class="calendar-cell {{ $isAvailable ? 'available' : 'has-booking' }} {{ $isToday ? 'today' : '' }} {{ $isWeekend ? 'weekend' : '' }}" 
                                data-date="{{ $day['date']->format('Y-m-d') }}">
                                <div class="calendar-day-content">
                                    <div class="calendar-day-number">{{ $day['day'] }}</div>
                                    @if (!$isAvailable)
                                        <div class="booking-status">
                                            <i class="bi bi-x-circle-fill"></i>
                                            <small>จองแล้ว</small>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

@section('content')

{{ Form::open(URL::current(), 'GET', array('class' => 'well form-filter clearfix')) }}
<div class="item">
<label>Tarih Aralığı</label>
{{ Form::text('datestart', Input::get('datestart', date('d.m.Y - 00:00')), array('class' => 'input-date datetimepicker')) }} 
{{ Form::text('dateend', Input::get('dateend', date('d.m.Y - 23:59')), array('class' => 'input-date datetimepicker')) }}
</div>
<div class="item">
<label>Durum</label>
{{ Form::select('status', Cdr::get_options('status'), Input::get('status'), array('class' => 'input-medium')) }}
</div>

<div style="clear: both"></div>

<div class="item">
<label>Arayan/Aranan</label>
{{ Form::text('extension', Input::get('extension'), array('class' => 'input-medium')) }}
</div>
<div class="item">
<label>Arama Yönü</label>
{{ Form::select('calldir', Cdr::get_options('calldir'), Input::get('calldir'), array('class' => 'input-small', 'id' => 'calldir-select')) }}
</div>
<div class="item">
<label>Kapsam</label>
{{ Form::select('scope', Cdr::get_options('scope'), Input::get('scope'), array('class' => 'input-medium', 'id' => 'scope-select')) }}
</div>
@if (Input::get('sort'))
{{ Form::text('sort', Input::get('sort'), array('class' => 'hide')) }}
@endif
@if (Input::get('dir'))
{{ Form::text('dir', Input::get('dir'), array('class' => 'hide')) }}
@endif
@if (Input::get('per_page'))
{{ Form::text('per_page', Input::get('per_page'), array('class' => 'hide')) }}
@endif
<div class="item">
{{ Form::submit('Filtrele', array('class' => 'btn btn-primary', 'style' => 'margin-right: 8px')) }}
{{ Html::link('cdr', 'Sıfırla', array('class' => 'btn')) }}
</div>
{{ Form::close() }}

@if (empty($cdrs->results))
  <div class="alert alert-error">Bu kritere uygun kayıt bulunamadı.</div>
@else
<table class="table table-bordered table-striped cdr-table">
  <thead>
    <th>{{ $cdrs->sortlink('calldate', 'Tarih - Saat') }}</th>
    <th>{{ $cdrs->sortlink('src', 'Arayan') }}</th>
    <th>{{ $cdrs->sortlink('dst', 'Aranan') }}</th>
    <th>{{ $cdrs->sortlink('disposition', 'Durum') }}</th>
    <th>{{ $cdrs->sortlink('billsec', 'Süre') }}</th>
    <th style="width: 75px">Ses Kaydı</th>
  </thead>
  <tbody>
  @foreach ($cdrs->results as $cdr)
    <tr>
      <td>{{ date('d.m.Y', strtotime($cdr->calldate)) . ' - ' . date('H:i:s', strtotime($cdr->calldate)) }}</td>
      <td>{{ Cdr::format_channel($cdr, 'src') }}</td>
      <td>{{ Cdr::format_channel($cdr, 'dst') }}</td>
      <td>{{ __("misc.$cdr->disposition") }}</td>
      <td>{{ Cdr::format_billsec($cdr->billsec) }}</td>
      <td>@if ($cdr->userfield)
        {{ Form::hidden('uniqueid', $cdr->uniqueid) }}
        {{ Form::hidden('calldate', strtotime($cdr->calldate)) }}
        <a class="btn btn-mini btn-listen" data-toggle="modal" href="#listen">Dinle</a>
        <a class="btn btn-mini" href="{{ URL::to('cdr/download/'.$cdr->uniqueid.'/'.strtotime($cdr->calldate)) }}">İndir</a>
        @endif
      </td>
    </tr>
  @endforeach
    <tr>
      <td colspan="6">
        <strong>Toplam arama sayısı: </strong>{{ $cdrs->total }}
      </td>
    </tr>
  </tbody>
</table>

<!-- Modal window -->
<div class="modal fade" id="listen">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Ses Kaydı</h3>
  </div>
  <div class="modal-body">
    <p><span class="spinner"></span></p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Kapat</a>
  </div>
</div>

{{ $cdrs->links() }}
<div id="per-page-container">
{{ Form::select('per_page', $per_page_options, Input::get('per_page', 10), array('id' => 'per-page', 'class' => 'input-mini')) }}
 / sayfa
</div>
{{ Form::hidden('total', $cdrs->total, array('id' => 'total')) }}
{{ Form::hidden('page', Input::get('page', 1), array('id' => 'page')) }}
@endif

@endsection
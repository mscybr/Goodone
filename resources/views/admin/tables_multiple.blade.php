
@php

        function draw_table($title, $headers, $data){
@endphp

<div class="card mt-3">
    <h5 class="card-header">{{$title}}</h5>
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <?php foreach( $headers as $header ){?>
                <th>{{$header}}</th>
            <?php }?>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            <?php foreach( $data as $element ){?>
                <tr>
                    <?php foreach( $element as $sub_element ){
                        switch ($sub_element->type) {
                            case 'string': ?>
                                <td>{{$sub_element->value}}</td>
                                <?php break; ?>
                            <?php case 'image': ?>
                                <td><a href="{{$sub_element->value}}" target="_blank" rel="noopener noreferrer"><img style="max-height: 100px;" src="{{$sub_element->value}}" alt=""></a></td>
                                <?php break; ?>
                            <?php case 'anchor': ?>
                                <td><a href="{{$sub_element->href}}" class="btn btn-{{$sub_element->color}}">{{$sub_element->value}}</a></td>
                                <?php break; ?>
                            <?php case 'modal': ?>
                                <td><button type="button" data-bs-toggle="modal" data-bs-target="#{{ $sub_element->target }}" class="btn btn-{{$sub_element->color}}">{{$sub_element->value}}</button></td>
                                <?php break; ?>
                    <?php }} ?>

                </tr>
            <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  @php } @endphp


@php
foreach ($tables as $table) {
  draw_table($table["title"], $table["headers"], $table["data"]);
}
@endphp



<?php

// $wrapper_function is a function to customize the div that holds the fields together
    function multi_fields_card_builder( $wrapper_function, $fields, $action, $field_title=null, $enctype=null, $method="POST" ){?>
    <form method="<?= $method ?>" action="<?= $action ?>" <?= $enctype !== null ? "enctype='$enctype'" : ""?>>
        @csrf
        <?php $wrapper_function(multi_fields_card($fields, $field_title)); ?>
    </form>
    <?php } ?>
<?php function single_field_card_builder( $wrapper_function, $fields, $action, $enctype=null, $method="POST" ){?>
    <form method="<?= $method ?>" action="<?= $action ?>" <?= $enctype !== null ? "enctype='$enctype'" : ""?>>
        @csrf
        <?php foreach( $fields as $field ){
            $wrapper_function(single_field_card($field->name, $field->type, $field->label, $field->title, $field->value, $field->placeholder, $field->note));
        }?>
    </form><?php } ?>
 <?php function single_field_card( $field_name, $field_type="text", $field_label, $field_title=null, $default_value=null, $placeholder=null, $note=null, $options=null ){?>
    <div class="card mb-3">
        <?php if( $field_title !== null ){?>
            <h5 class="card-header"><?= $field_title ?></h5>
        <?php } ?>
        <div class="card-body mb-5">
            <div>
            <label for="field" class="form-label"><?= $field_label ?></label>
            @if ($field_type == "textarea")
                <textarea name="<?=$field_name?>" class="form-control" id="field" placeholder="<?=$placeholder?>"><?=$default_value?></textarea>
            @elseif ($field_type == "select")
                <select name="<?=$field_name?>" class="form-control" id="field" placeholder="<?=$placeholder?>">
                    @foreach ($options as $option)
                        <option value="<?= $option["value"] ?>"><?= $option["name"] ?></option>
                    @endforeach
                </select>
            @else
                <input type="<?= $field_type ?>" name="<?=$field_name?>" class="form-control" id="field" placeholder="<?=$placeholder?>" value="<?=$default_value?>" >
            @endif
            <?php if( $note !== null){?>
                <div id="defaultFormControlHelp" class="form-text">
                    <?= $note ?>
                </div>
            <?php } ?>
            </div>
            <button class="btn btn-success mt-3">Submit</button>
        </div>
    </div>
<?php  };
 function multi_fields_card( $fields, $field_title=null ){
    ?>
    <div class="card">
        <?php if( $field_title !== null ){?>
            <h5 class="card-header"><?= $field_title ?></h5>
        <?php } ?>
        <div class="card-body mb-3">
            <?php foreach($fields as $field ){?>
                <div>
                    <label for="field" class="form-label"><?= $field->label ?></label>
                     @if ($field->type  == "textarea")
                        <textarea name="<?=$field->name?>" class="form-control" id="field" placeholder="<?=$field->placeholder?>"><?=$field->value?></textarea>
                    @elseif ($field->type == "select")
                        <select name="<?=$field->name?>" class="form-control" id="field"  placeholder="<?=$field->placeholder?>">
                             <?php foreach($field->options as $option ){?>
                                <option value="<?= $option["value"] ?>"><?= $option["name"] ?></option>
                            <?php } ?>
                        </select>
                    @else
                        <input type="<?= $field->type ?>" name="<?=$field->name?>" class="form-control" id="field" placeholder="<?=$field->placeholder?>" value="<?=$field->value?>" >
                    @endif
                    <?php if( $field->note !== null){?>
                        <div id="defaultFormControlHelp" class="form-text">
                            <?= $field->note ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <button class="btn btn-success mt-3">Submit</button>
        </div>
    </div>
<?php  }; ?>


@php
        if($type == "single_field_card_builder") single_field_card_builder($wrapper, $data, $action, $enctype, $method);
        if($type == "multi_fields_card_builder") multi_fields_card_builder($wrapper, $data, $action, $title, $enctype, $method);
@endphp



<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div id="scheduler_here" class="dhx_cal_container" style='width:100%; height:100%;'>
    <div class="dhx_cal_navline">
        <div class="dhx_cal_prev_button">&nbsp;</div>
        <div class="dhx_cal_next_button">&nbsp;</div>
        <div class="dhx_cal_today_button"></div>
        <div class="dhx_cal_date"></div>
        <div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
        <div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
        <div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>
    </div>
    <div class="dhx_cal_header">
    </div>
    <div class="dhx_cal_data">
    </div>
</div>

<script src="<?php echo base_url(); ?>js/dhtmlxscheduler.js" type="text/javascript" charset="utf-8"></script>

<script src="<?php echo base_url(); ?>js/locale/locale_es.js" charset="utf-8"></script>


<script type="text/javascript" charset="utf-8">
    function initTurnos() {
        scheduler.config.first_hour = 09;
        scheduler.init('scheduler_here', new Date(<?php echo date('Y'); ?>, <?php echo date('m') - 1; ?>, <?php echo date('d'); ?>), "week");
        scheduler.load("<?php echo base_url(); ?>data/events.xml");

        scheduler.attachEvent("onEventCreated", function (e) {
            alert("evento creado")
            return true;
        });
        scheduler.attachEvent("onEventSave", function (id, ev, is_new) {
            
            alert("id: "+id+" data: "+ev.toSource());
            if (!ev.text) {
                alert("Text must not be empty");
                return false;
            }
            /*if (!ev.text.length < 20) {
                alert("Text too small");
                return false;
            }*/
            save_turno(ev);
            return true;
        })

    }

    function save_turno(t) {

        $.ajax({
            type: "POST",
            url: '<?php echo site_url('admin/turnos') ?>/save_evento',
            data: {myData: JSON.stringify(t), '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
            dataType: 'json',
            async: false,
            success: function (data)
            {
                alert(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {

                alert("error: "+xhr.toSource());
            }
        });
    }

</script>
<script>//$(document).ready(function() { initTurnos(); });</script>
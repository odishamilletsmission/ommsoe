
    <div class="container">
    <div class="row">
        <div class="col-md-2">
            <ul class="list-group district-info">
                <li class="list-group-item"><span class="text-dark-green text-bold" id="total_districts">0</span><br>Districts</li>
                <li class="list-group-item"><span class="text-dark-green text-bold" id="total_blocks">0</span><br>Blocks</li>
                <li class="list-group-item"><span class="text-dark-green text-bold"><?=$gps['total_gps']?></span><br>GPs</li>
                <li class="list-group-item"><span class="text-dark-green text-bold"><?=$gps['total_villages']?></span><br>Villages</li>
                <li class="list-group-item"><span class="text-dark-green text-bold"><?=$farmers['total_farmers']?></span><br>Farmers</li>
            </ul>
        </div>
        <div class="col-md-8">
            <div id="map-area">
                <?=$svg_map?> 
            </div>
        </div>
    </div>
    </div>

    <?php js_start(); ?>
    <script>
        var mapinfo;
        $(function () {
            $.ajax({
                url:'<?=$map_url?>',
                dataType:'JSON',
                success:function (json) {
                    if(json.success){
                        mapinfo = json;
                        showDistInfo(mapinfo);
                        //&#xf041;
                        $('#hdQcdetails .dist-block').each(function () {
                            mapid = parseInt($(this).data('dist-id'));
                            dist = mapinfo.dists[mapid];
                            if(dist.is_omm){
                                $('g text.dist-'+mapid).html('&#xf041;')
                                if(dist.total_blocks >= 1 && dist.total_blocks <= 3){
                                    $('#dist-'+mapid).addClass('bg-scale1')
                                    this.classList.add("bg-scale1");
                                }
                                if(dist.total_blocks >= 4 && dist.total_blocks <= 5){
                                    $('#dist-'+mapid).addClass('bg-scale2')
                                    this.classList.add("bg-scale2");
                                }
                                if(dist.total_blocks >= 6 && dist.total_blocks <= 8){
                                    $('#dist-'+mapid).addClass('bg-scale3')
                                    this.classList.add("bg-scale3");
                                }
                                if(dist.total_blocks >= 9){
                                    $('#dist-'+mapid).addClass('bg-scale4')
                                    this.classList.add("bg-scale4");
                                }
                                var html = '<strong><u>'+dist.name+'</u></strong><br>'
                                    +dist.total_blocks
                                    +' blocks<br>'
                                    +dist.total_chc
                                    +' CHCs<br>'
                                    +dist.total_csc
                                    +' CSCs<br>'
                                    +dist.total_farmers
                                    +' Farmers<br>';
                                $('#dist-'+mapid)
                                    .attr('data-toggle',"tooltip")
                                    .attr('data-placement','top')
                                    .attr('data-html','true')
                                    .attr('title',html)
                            }
                        })
                        $('#total_districts').text(mapinfo.total_districts)
                        $('#total_blocks').text(mapinfo.total_blocks)
                    }
                },
                error:function () {
                    alert('something went wrong while fetching info');
                }
            })

            $('.dist-block').mouseover(function () {
                mapid = parseInt($(this).data('dist-id'));
                mapinfo1 = mapinfo.dists[mapid];
                $('#info-label').text(mapinfo1.name);
                showDistInfo(mapinfo1)
            })

            $('.dist-block').mouseout(function () {
                $('#info-label').text('All OMM Districts');
                showDistInfo(mapinfo);
            })

        })
        function showDistInfo(mapinfo) {
            $('.total-blocks').text(mapinfo.total_blocks);
            $('.area-coverage').text(mapinfo.area_coverage);
            $('.total-farmers').text(mapinfo.total_farmers);
            $('.total-chc').text(mapinfo.total_chc);
            $('.total-csc').text(mapinfo.total_csc);
        }
    </script>
    <?php js_end(); ?>
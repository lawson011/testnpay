<script>
 window.onload = function(){
 
     $.ajax({
               type:'GET',
               url:'/admin/loanstatistics',
               
               success:function(data) {

                  var newData = [];
                  days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']

                data1 = []
                data2 = []
                days.forEach(element => data1.push(data['disbursed_loan_days'][element]));
                days.forEach(element=>data2.push(data['repaid_loan_days'][element]));
                new Chartist.Line('.ct-chart', {
                labels: ['Sunday','Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday','Saturday'],
                series: [
                    {   
                        name:'Disbursed Loans',
                        data:data1
                    },
                    {
                       
                        name:'Repaid Loans',
                        data:data2
                    }
                ]
                }, {
                    seriesBarDistance: 20,
                    axisX: {
                        showGrid: false
                    },
                    axisY: {
                        onlyInteger: true,
                        offset: 20
                    }
                //   width: 320,
                //   height: 240
                });

                var $tooltip = $('<div  class="tooltip tooltip-hidden"></div>').appendTo($('.ct-chart'));

                $(document).on('mouseenter', '.ct-point', function() {
                var seriesName = $(this).closest('.ct-series').attr('ct:series-name'),
                    value = $(this).attr('ct:value');
                  
                $tooltip.text(seriesName + ': ' + value);
                $tooltip.removeClass('tooltip-hidden');
                $tooltip.addClass('show');
                });

                $(document).on('mouseleave', '.ct-point', function() {
                    $tooltip.removeClass('show');
                $tooltip.addClass('tooltip-hidden');
                
                });

                $(document).on('mousemove', '.ct-point', function(event) {
                $tooltip.css({
                    left: event.offsetX - $tooltip.width() / 2,
                    top: event.offsetY - $tooltip.height() - 20
                });
                });
                $('[data-toggle="tooltip"]').tooltip();
               }
                            });
    }

</script>
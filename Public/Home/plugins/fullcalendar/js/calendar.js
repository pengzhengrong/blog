    $(document).ready(function() {


        /* initialize the external events
        -----------------------------------------------------------------*/



        $('#external-events div.external-event').each(function() {

            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            // 这是可拖动事件的节点
            var eventObject = {
                title: $.trim($(this).text()) // use the element's text as the event title
            };

            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject);
            // console.log( $(this).data('eventObject') );
            
            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });
            
        });


        /* initialize the calendar
        -----------------------------------------------------------------*/
        
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            // timeFormat: 'H(:mm)', // uppercase H for 24-hour clock
            events:"/Home/Schedule/index.html", //获取初始化日程的json数据
            dayClick: function(date, allDay, jsEvent, view) {
               var selDate =$.fullCalendar.formatDate(date,'yyyy-MM-dd');
               $.fancybox({
                'type':'ajax',
                'href':'/Home/Schedule/schedule_add.html?date='+selDate
            });
                /*var url = '/Home/Schedule/event_add.html?date='+selDate;art.dialog({title:'添加新的事件',id:'add',iframe:url,width:'700',height:'300'});*/
            },
            eventClick: function(calEvent, jsEvent, view) {
                $.fancybox({
                    'type':'ajax',
                    'href':'schedule_edit.html?id='+calEvent.id
                });
            },
            // eventDragStop:function(calEvent, jsEvent, view) {alert(1);},
            eventResize:function( calEvent, delta, revertFunc, jsEvent, ui, view ) {
                editCalendar(calEvent);
            },
            eventDrop:function( calEvent, delta, revertFunc, jsEvent, ui, view ){
               editCalendar(calEvent);
            },
           /* eventMouseover:function(calEvent, jsEvent, view){},*/
            editable: true, //不可通过拖长事件而修改时间
            droppable: true, // this allows things to be dropped onto the calendar !!!
            drop: function(date, allDay) { // this function is called when something is dropped

                // retrieve the dropped element's stored Event Object
                var originalEventObject = $(this).data('eventObject');
               /* originalEventObject = {
                    title:"test"
                }           */     
                // we need to copy it, so that multiple events don't have a reference to the same object
                var copiedEventObject = $.extend({}, originalEventObject);
                
                // date = 'Sun Jul 31 2016 00:00:00 GMT+0800 (CST)';


                // assign it the date that was reported
                copiedEventObject.start = date;
                copiedEventObject.allDay = allDay;

                // console.log( date+"  "+allDay );
                // console.log( originalEventObject );
                // console.log( copiedEventObject);
                var selDate =$.fullCalendar.formatDate(copiedEventObject.start,'yyyy-MM-dd');
                var pars = {
                    startdate:selDate,
                    event:copiedEventObject.title,
                    isallday:1
                };
                // console.log(pars);
                $.post('/Home/Schedule/schedule_add.html',pars,function(data){
                    if( data != 1 ) {
                        alert(data);
                    }
                });
                

                // render the event on the calendar
                // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                // $('#calendar').fullCalendar( 'renderEvent',  copiedEventObject,  true );
                $('#calendar').fullCalendar('refetchEvents'); //重新获取所有事件数据
                
                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove();
                }
                
            }
        });

        //修改日程
        function editCalendar(calEvent) {
             // console.log(calEvent.end);
                var startdate =$.fullCalendar.formatDate(calEvent.start,'yyyy-MM-dd');
                var enddate = 0,e_hour=0,e_minute=0,isend=0;
                 //当截止时间和起始时间重叠的时候，end == null
                if( calEvent.end != null ) {
                     enddate = $.fullCalendar.formatDate(calEvent.end,'yyyy-MM-dd');
                     e_hour = $.fullCalendar.formatDate(calEvent.end,'H');
                     e_minute = $.fullCalendar.formatDate(calEvent.end,'m');
                     isend = 1;
                }
                var pars = {
                    id:calEvent.id,
                    startdate:startdate,
                    s_hour: $.fullCalendar.formatDate(calEvent.start,'H'),
                    s_minute: $.fullCalendar.formatDate(calEvent.start,'m'),
                    event:calEvent.title,
                    isallday:calEvent.allDay,
                    enddate:enddate,
                    e_hour: e_hour,
                    e_minute: e_minute,
                    isend:isend
                };
                // console.log(pars);
                $.post('/Home/Schedule/schedule_edit.html',pars,function(data){
                    if( data != 1 ) {
                        alert(data);
                    }
                });
                $('#calendar').fullCalendar('refetchEvents'); //重新获取所有事件数据
        }

        // initDate('title','2016-08-03');
        // initDate('title','Mon Aug 01 2016 00:00:00 GMT+0800 (CST)');
        //by myself
      /*  function initDate(title,date) {
             // retrieve the dropped element's stored Event Object
             var originalEventObject = $(this).data('eventObject');
             originalEventObject = {
                title:title
            };                
                // we need to copy it, so that multiple events don't have a reference to the same object
                var copiedEventObject = $.extend({}, originalEventObject);
                
                // date = 'Sun Jul 31 2016 00:00:00 GMT+0800 (CST)';


                // assign it the date that was reported
                copiedEventObject.start = date;
                copiedEventObject.end = '2016-08-05';
                copiedEventObject.allDay = true;
                // console.log( date );
                console.log( copiedEventObject );
                $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
            }*/

        //因为拖动事件是动态增加的，所以此事件无法调用，需要使用事件代理
      /*  $('.fc-event').dblclick(function(event){
            var _this = $(event.currentTarget);
            console.log(_this);
            _this.remove();
        });*/

        //拖动事件的代理
  /*      $('.fc-content').on('dblclick','.fc-event',function(event){
            var _this = $(event.currentTarget);
            // console.log(_this);
            // _this.remove();
            $('#calendar').fullCalendar( 'removeEvents'  );
        });*/

        

      /* Bootstrap styles
      -----------------------------------------------------------------*/
      // $('.fc-header').hide();
      

      /*var currDate = $('#calendar').fullCalendar('getDate');
      $('#calender-current-day').html($.fullCalendar.formatDate(currDate, "ddd"));
      $('#calender-current-date').html($.fullCalendar.formatDate(currDate, "yyyy-MM-dd"));
      $('#cal-prev').click(function(){

     $('#cal-today').click(function() {
        alert(1);
            // $('#calendar').fullCalendar('today');
            // currDate = $('#calendar').fullCalendar('getDate');
            // console.log(currDate);
            // $('#calender-current-day').html($.fullCalendar.formatDate(currDate, "ddd"));
            // $('#calender-current-date').html($.fullCalendar.formatDate(currDate, "yyyy-MM-dd"));
        });

        $('#calendar').fullCalendar( 'prev' );
        currDate = $('#calendar').fullCalendar('getDate');
         // console.log(currDate);
         $('#calender-current-day').html($.fullCalendar.formatDate(currDate, "ddd"));
         $('#calender-current-date').html($.fullCalendar.formatDate(currDate, "yyyy-MM-dd"));
     });
      $('#cal-next').click(function(){
        $('#calendar').fullCalendar( 'next' );
        currDate = $('#calendar').fullCalendar('getDate');       
        $('#calender-current-day').html($.fullCalendar.formatDate(currDate, "ddd"));
        $('#calender-current-date').html($.fullCalendar.formatDate(currDate, "yyyy-MM-dd"));
    });
      $('#change-view-month').click(function(){
        $('#calendar').fullCalendar('changeView', 'month');
    });
      $('#change-view-week').click(function(){
         $('#calendar').fullCalendar( 'changeView', 'agendaWeek');
     });
      $('#change-view-day').click(function(){
        $('#calendar').fullCalendar( 'changeView','agendaDay');
    });*/
  });
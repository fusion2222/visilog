<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <title>{{ env('APP_NAME') }}</title>
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: Arial, Helvetica, sans-serif;
                margin: 0;
            }
            .content{
                padding: 30px;
            }
            .log-list{
                list-style-type: none; 
                margin: 0;
                padding: 0;
            }
            .log-list--log{
                background-color: #eee;
                margin-bottom: 2px;
                padding: 10px 15px 17px;
                cursor: pointer;
            }
            .log-list--log:hover{
                background-color: #636b6f;
                color: white;
            }
            .marker{
                display: inline-block;
                width: 25px;
                height: 25px;
                background-color: inherit;
                border-radius: 25px;
                position: relative;
                top: 7px;
                left: 0;
            }
            .cell{
                display: inline-block;
                padding: 0 15px;
            }
            .delete-button{
                padding: 15px;
                margin-bottom: 10px;
                background-color: #636b6f;
                border: 0;
                color: white;
                border-radius: 25px;
                cursor: pointer;
            }
            .delete-button:hover{
                background-color: #3d4042;
            }
        </style>
    </head>
    <body>
        <div class="content">
            <h1>{{ env('APP_NAME') }}</h1>
            <button class="delete-button js-delete">Delete</button>
            <ul class="log-list js-log-list"></ul>
        </div>

        <script type="text/javascript">
            var App = {
                SHOW_RECORDS: 15,
                TIMEOUT: 2000,

                DATA_URL: @json(route('data', [], false)),
                DELETE_URL: @json(route('delete', [], false)),

                CLASS_LOG_LIST: 'js-log-list',
                CLASS_DELETE_BUTTON: 'js-delete',
                EVENT_REFRESH: new Event('refresh'),
                EVENT_PURGE: new Event('purge'),

                createRecordDOM: function(record_data){
                    /*    EXAMPLE:
                     *
                     *    <li class="log-list--log">
                     *       <span class="marker" style="background-color: #color;"></span>
                     *       <span class="cell">id</span>
                     *       created_at
                     *   </li>
                     */
                    var record = document.createElement('li');
                    record.className = 'log-list--log';

                    var marker = document.createElement('span');
                    marker.className = 'marker';
                    marker.setAttribute('style', 'background-color: #' + record_data.color);
                    record.appendChild(marker);

                    var cell = document.createElement('span');
                    cell.className = 'cell';
                    cell.innerHTML = record_data.id;
                    record.appendChild(cell);

                    var end_cell = document.createElement('span');
                    end_cell.innerHTML = record_data.created_at;
                    record.appendChild(end_cell);

                    return record;
                },
                assignEvents: function(){

                    this.log_list.addEventListener('refresh', function(e){
                        var xhr = new XMLHttpRequest();
                        xhr.open('GET', App.DATA_URL + '?limit=' + String(App.SHOW_RECORDS), true);
                        xhr.onload = function() {
                            if (xhr.status == 200) {
                                var data = JSON.parse(xhr.responseText);
                                App.log_list.dispatchEvent(App.EVENT_PURGE);

                                for (var i = 0; i < data.length; i++){
                                    App.log_list.appendChild(
                                        App.createRecordDOM(data[i])
                                    );
                                }

                            }else {
                                alert(String(xhr.status) + 'Request failed.');
                            }

                            setTimeout(function(){
                                App.log_list.dispatchEvent(App.EVENT_REFRESH);
                            }, App.TIMEOUT);

                        };
                        xhr.send();
                    });

                    this.log_list.addEventListener('purge', function(e){
                        while(this.firstChild){
                            this.removeChild(this.firstChild);
                        }
                    });

                    document.getElementsByClassName(this.CLASS_DELETE_BUTTON)[0].onclick = function(){
                        var xhr = new XMLHttpRequest();
                        xhr.open('GET', App.DELETE_URL, true);
                        xhr.send();
                    };

                },
                init: function(){
                    this.log_list = document.getElementsByClassName(this.CLASS_LOG_LIST)[0];
                    this.assignEvents();
                    document.getElementsByClassName(this.CLASS_LOG_LIST)[0].dispatchEvent(App.EVENT_REFRESH);
                }
            };
            App.init();
        </script>
    </body>
</html>

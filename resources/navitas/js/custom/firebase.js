var firebase_chat = firebase_env.child('chat');
var firebase_user = firebase_env.child('user');
var firebase_messages = firebase_chat.child('message');

var cachedFbUserData = [];
var $headerThreads = [];

var firebase_header_chat =
    {
        upload_threads_init: function ($init)
        {
            var $msgCont = [];
            firebase_chat.child("thread").on('child_added', function (snapshot)
            {
                var $members = snapshot.val().members;
                $.each($members,function(i,v){
                    if(v.member_id == auth_user_id){
                        firebase_messages.orderByChild('thread_id').equalTo(snapshot.key()).limitToLast(1).on("child_added", function (snapshot2){
                            var $msg = snapshot2.val();

                            if(!$msgCont[snapshot.key()]) {
                                $msgCont[snapshot.key()] = $msg;
                                $headerThreads.push($msgCont);
                            }

                            if(!cachedFbUserData[$msg.user_id]) {
                                firebase_user.child($msg.user_id).once('value', function (userSnapshot) {
                                    cachedFbUserData[$msg.user_id] = userSnapshot.val();
                                    firebase_header_chat.append_threads_last_message($msg);
                                });
                            } else{
                                firebase_header_chat.append_threads_last_message($msg);
                                if(!$init){
                                    $head = cachedFbUserData[$msg.user_id].full_name + " wrote:";
                                    $ico = 'https://app.navitas.eu.com/'+cachedFbUserData[$msg.user_id].avatar;
                                    $msg = $msg.message + "\n" + $msg.created_at;
                                    $ntfData = {'head':$head, 'icon':$ico, 'message':$msg};
                                    browserNotify($ntfData);
                                }
                            }
                            firebase_header_chat.update_threads_counter($headerThreads.length);
                        });
                    }
                });
            });
            $init = false;
        },
        append_threads_last_message: function($data)
        {
            var $task_details_template = $('#append_threads_last_message');
            var task_details_template_content = $task_details_template.html();
            var append_service = function()
            {
                var template_compiled = Handlebars.compile(task_details_template_content);
                Handlebars.compile(task_details_template_content);
                theCompiledHtml = template_compiled($data);
                $('#chat_threads_header_list').find('li[thread_id='+$data.thread_id+']').remove();
                $('#chat_threads_header_list').find('span#no_header_threads').remove();
                $('.header_main_content #chat_threads_header_list').prepend(theCompiledHtml);

            };
            append_service();
        },
        update_threads_counter: function($count){
            $('#chat_threads_counter').text($count);
            $cnt1 = parseInt($('#alerts_counter').text());
            $cnt2 = $count;
            $('#header_chat_alerts_counter').text(($cnt1+$cnt2));
        }
    };

$(function()
{
    firebase_header_chat.upload_threads_init(true);
});
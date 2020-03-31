<!-- The timeline -->
<ul class="timeline timeline-inverse">
    <?php
    $tempDate = '';
    $Helper = new Helper();
    //echo "<pre>"; print_r($data['message_reports']); echo "</pre>";
    if (isset($data['timeline']) && !empty($data['timeline'])) {
        $counter = 1;
        foreach ($data['timeline'] as $key => $timeline) {
           // die($timeline->getOriginal('created_at'));
            $lableDate = date('j M Y', strtotime($timeline->getOriginal('created_at')));
            if($lableDate != $tempDate){
            $lableClass = $counter % 2 ? 'bg-green' : 'bg-red';
            echo '<li class="time-label">
                    <span class="' . $lableClass . '">
                      ' . $lableDate . '
                    </span>
                  </li>';
            $counter++;
            }
            $tempDate = $lableDate;
            //foreach ($timeline as $key => $timelineData) {
                $message_sent = $Helper->getTimeDuration($timeline->getOriginal('created_at')); //date('j M h:i a', strtotime($timelineData['created_at']));
                if ($timeline->type == 'Message') {
                    ?>
                    <!-- timeline item -->
                    <li>
                        <i class="fa fa-support bg-red"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fa fa-clock-o"></i> <?php echo $message_sent; ?></span>
                            <h3 class="timeline-header"><a href="#"><?php echo $timeline->fromUser['first_name'].' '. $timeline->fromUser['last_name']; ?></a> sent you an message</h3>
                            <div class="timeline-body">
                                <?php
                                $message_content = strlen($timeline->message->content) > 203 ? substr($timeline->message->content, 0, 200) . '...' : $timeline->message->content;
                                echo $message_content;
                                ?>
                            </div>
                            <div class="timeline-footer">
                                <a  href="" class="btn btn-primary btn-xs msg-popup-modal" data-toggle="modal" data-controller="messages"  data-id="<?php echo $timeline->message->id; ?>" data-post="data-php" data-action="view_user_message">Read More</a>                                    
                                <a class="btn btn-danger btn-xs">Delete</a>
                            </div>
                        </div>
                    </li>
                    <!-- END timeline item -->    
                <?php } elseif ($timeline->type == 'Notification') { ?>
                    <!-- timeline item -->                      
                    <li>
                        <i class="fa fa-envelope bg-aqua"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fa fa-clock-o"></i> <?php echo $message_sent; ?></span>
                            <h3 class="timeline-header no-border"><a href="#"><?php echo $timeline->notification->title; ?></a> <?php echo $timeline->notification->content; ?></h3>
                        </div>
                    </li>
                    <!-- END timeline item -->   
                <?php
                }
            //} //end foreach($timeline as $key => $data)

        } //end foreach($data['timeline'] as $key => $timeline)
    } //end of if(isset($data['timeline']) && !empty($data['timeline']))
    ?>  

    <li>
        <i class="fa fa-clock-o bg-gray"></i>
    </li>
    <li style="float: right; margin-top: -50px; padding-right: 16px;">{!! $data['timeline']->links() !!}</li>
</ul>
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\PostFiles;
use DB;
use Aws\MediaConvert\MediaConvertClient;
use Aws\Credentials\CredentialProvider;
use Aws\Exception\AwsException;
use Aws\MediaConvert\Exception\MediaConvertException as MediaConvertError;

class ProcessPostFilesForMetaData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image_process:post {post_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
    }

    private $mediaConvertClient;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $duration = 0;
        $thumb_image = '';
        
        $post_id = $this->argument('post_id');
        $data = DB::table('posts')->where(['id'=>$post_id])->get()->first();
        
        try{
            $image_info = getimagesize(get_uploaded_image_url($data->file,'post_image_upload_dir'));
        }catch(Exception $e){
            $image_info=[];
        }
        // printr($data);
        // echo "here"; exit;
        $thumb_image = $data->file;

        
        // $this->media_convert_client = \App::make('aws')->createClient('MediaConvert', [
        //     'version' => 'latest',
        //     'endpoint' => "https://xdwfvckxc.mediaconvert.ap-southeast-1.amazonaws.com",
        // ]);


        // $media_convert_job_ids = [];


        // if(!$image_info) {

        //     $capture_frame_second   = 1;
        //     $ffprobe = \FFMpeg\FFProbe::create();
        //     $ffmpeg     = \FFMpeg\FFMpeg::create();

        //     $video = $ffmpeg->open(get_uploaded_image_url($data->file,'post_image_upload_dir'));
        //     $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds($capture_frame_second));

        //     $video_capture_filename =  public_path().'/' .time().".jpg";
        //     $thumb_name = time().uniqid()."thumb.jpg";

        //     $frame->save($video_capture_filename);

        //     $contents = file_get_contents($video_capture_filename);
        //      $ext = pathinfo($video_capture_filename, PATHINFO_EXTENSION);
        //      $thumb_image = $name = time().uniqid()."_thumb.".$ext;
        //     \Storage::disk(config('global.upload_bucket'))->put(config('global.post_image_upload_dir').$name, $contents);

        //      //DB::table('posts')->where(['id'=>$post_id])->update(['thumb_image'=>$name]);

        //     $image_info = getimagesize($video_capture_filename);
        //     @unlink($video_capture_filename);

        //     $stream_video = $ffprobe
        //                         ->streams(get_uploaded_image_url($data->file,'post_image_upload_dir'))  // extracts streams informations
        //                         ->videos()                              // filters video streams
        //                         ->first();
        //     $duration = ceil( $stream_video->get("duration") );
        // }
        // else {
        //     $thumb_image = $data->file;
        // }

        

        $extension = pathinfo(get_uploaded_image_url($data->file,'post_image_upload_dir'),PATHINFO_EXTENSION);
        //printr($d);

        PostFiles::where(['post_id'=>$post_id])->delete();

        $file = new PostFiles();
        $file->url =$data->file;
        $file->post_id = $post_id;
        $file->is_default = 1;
        $file->width = $image_info[0]??0;
        $file->height = $image_info[1]??0;
        $file->extension = $extension;
        $file->duration = $duration;
        $file->thumb_image = $thumb_image;
        $file->save();

        $files = [];
        $items = explode(",",$data->extra_file_names);

        if(!empty($items)) {

            foreach($items as $item) {

                if($item != '') {
                    $thumb_image = $item;
                    $duration = 0;
                    $thumb_image ='';
                    try{
                        $image_info = getimagesize(get_uploaded_image_url($item,'post_image_upload_dir'));
                    }catch(Exception $e){
                        $image_info=[];
                    }
                    $extension = pathinfo(get_uploaded_image_url($item,'post_image_upload_dir'),PATHINFO_EXTENSION);
                    // if(!$image_info) {
                    //     $capture_frame_second   = 1;

                    //     $ffprobe = \FFMpeg\FFProbe::create();
                    //     $ffmpeg = \FFMpeg\FFMpeg::create();

                    //     $video = $ffmpeg->open(get_uploaded_image_url($item,'post_image_upload_dir'));
                    //     $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds($capture_frame_second));

                    //     $video_capture_filename =  public_path().'/' .time().".jpg";
                    //     $thumb_name = time().uniqid()."thumb.jpg";

                    //     $frame->save($video_capture_filename);

                    //     $contents = file_get_contents($video_capture_filename);
                    //     $ext = pathinfo($video_capture_filename, PATHINFO_EXTENSION);
                    //     $thumb_image = $name = time().uniqid()."_thumb.".$ext;
                    //     \Storage::disk(config('global.upload_bucket'))->put(config('global.post_image_upload_dir').$name, $contents);

                    //     //DB::table('posts')->where(['id'=>$post_id])->update(['thumb_image'=>$name]);

                    //     $image_info = getimagesize($video_capture_filename);
                    //     @unlink($video_capture_filename);

                    //     $stream_video = $ffprobe
                    //                         ->streams(get_uploaded_image_url($item,'post_image_upload_dir'))  // extracts streams informations
                    //                         ->videos()                              // filters video streams
                    //                         ->first();

                    //     $duration = ceil( $stream_video->get("duration") );




                    //     /* ----------------- */
                    //     // $codec_name     = $stream_video->get("codec_name");
                    //     // $tags           = $stream_video->get("tags");
                    //     // $side_data_list = $stream_video->get("side_data_list");
                        
                    //     // $encoder        = $tags["encoder"] ?? "";

                    //     // if( is_array($side_data_list) && isset($side_data_list[0]) && isset($side_data_list[0]["rotation"] ) ) {
                    //     //     $rotate = (int) $side_data_list[0]["rotation"];
                    //     // }
                    //     // else if( is_array($side_data_list) && isset($side_data_list["rotation"] ) ) {
                    //     //     $rotate = (int) $side_data_list["rotation"];
                    //     // }
                    //     // else if ( isset($tags["rotate"]) ) {
                    //     //     $rotate = (int) $tags["rotate"];
                    //     // }
                    //     // else {
                    //     //     $rotate = 0;
                    //     // }

                    //     // $video_meta = [
                    //     //                 "bit_rate" => $stream_video->get("bit_rate"),
                    //     //                 "width" => $stream_video->get("width"),
                    //     //                 "height" => $stream_video->get("height"),
                    //     //                 "codec_name" => $stream_video->get("codec_name"),
                    //     //                 "has_b_frames" => (int) $stream_video->get("has_b_frames"), //Number of B frames between IP frames or PP frames
                    //     //                 "level" => $stream_video->get("level"), //coded level
                    //     //                 "profile" => $stream_video->get("profile"), //profile
                    //     //                 "pix_fmt" => $stream_video->get("pix_fmt"),  //pixel format
                    //     //                 "color_range" => $stream_video->get("color_range"),
                    //     //                 "color_space" => $stream_video->get("color_space"),
                    //     //                 "color_transfer" => $stream_video->get("color_transfer"),
                    //     //                 "color_primaries" => $stream_video->get("color_primaries"),
                    //     //                 "r_frame_rate" => $stream_video->get("r_frame_rate"), //actual frame rate
                    //     //                 "avg_frame_rate" => $stream_video->get("avg_frame_rate"), //average frame rate
                    //     //                 "bit_rate" => $stream_video->get("bit_rate"), //bit_rate
                    //     //                 "bits_per_raw_sample" => $stream_video->get("bits_per_raw_sample"), //Bit depth of raw sample
                    //     //                 "refs" => $stream_video->get("refs"), //Video reference frame
                    //     //                 "encoder" => $encoder,
                    //     //                 // "rotation" => $rotation,
                    //     //                 // "rotate" => $rotate
                    //     //                 "rotate" => $rotate,
                    //     //                 "duration" =>  ceil( $stream_video->get("duration") ) -1
                    //     // ];
                        
                        
                    //     // $selected_file_name = $item;

                    //     // $raw_file_name = str_replace(".".$extension,"",$selected_file_name); //"file_example_mp4_1920"; //assign filename without extension
                    //     // $s3_path = config('global.post_image_upload_dir').$selected_file_name;


                    //     // $media_convert_fp_job_id = $this->aws_elemental_media_convert_start_job(
                    //     //      $s3_path,  //s3 full path without bucketname, here baucket name is s3://moda-staging/
                    //     //     $selected_file_name, //filename only stored in s3
                    //     //     (object) $video_meta,
                    //     //     "temp/".$raw_file_name."/",  //this will store the converted hls into a folder created using the raw filename
                    //     //     "",
                    //     //     ["270p", "360p", "480p", "1080p", "720p", "540p"]
                    //     // );
                
                    //     // $media_convert_job_ids[] = $media_convert_fp_job_id;

                        
                    //     /* ----------------- */












                    // } 
                    // else {
                    //     $thumb_image = $item;
                    // }

                    $file = new PostFiles();
                    $file->url =$item;
                    $file->post_id = $post_id;
                    $file->is_default = 0;
                    $file->width = $image_info[0]??0;
                    $file->height = $image_info[1]??0;
                    $file->extension = $extension;
                    $file->duration = $duration;
                    $file->thumb_image = $thumb_image;
                    $file->save();
                }
            }


            // $is_in_process = true;
    
            // $primary_job_id = $media_convert_job_ids[0];
            // $completed_jobs = [];
    
            // foreach($media_convert_job_ids as $job_id)
            //     $completed_jobs[$job_id] = false;
    
            // $is_all_job_completed = false;


            // while($is_in_process) {

            //     foreach($media_convert_job_ids as $job_id) {

            //         $job_result = NULL;

            //         try {

            //             $job_result =  $this->media_convert_client->getJob([
            //                 'Id' => $job_id,
            //             ]);

            //         } 
            //         catch (AwsException $e) {
            //             // output error message if fails
            //             echo $e->getMessage();
            //             echo "\n";
            //         }
                    
            //         if($job_result) {

            //             $job_status =  $job_result["Status"];
        
            //             if($job_status == "PROGRESSING" || $job_status == "SUBMITTED") {
            //             }
            //             else if($job_status == "COMPLETE" ) {
            //                 $completed_jobs[$job_id] = true;
            //             }
            //             else if($job_status == "CANCELED" || $job_status == "ERROR") {
                            
            //             }
            //         }
            //     }
    
            //     foreach($media_convert_job_ids as $job_id) {
            //         $is_all_job_completed = $completed_jobs[$job_id] ? true:false;
            //     }
    
            //     if($is_all_job_completed)  {
            //         //update any data here like hls url, cdn url 
            //         //or can update on  each job completion
            //         $is_in_process = false;
            //     }
    
            //     sleep(12);
            // }
     

        }
        //echo "php ".base_path()."/artisan hls:process ".$post_id;
        //exec("/usr/local/bin/php ".base_path()."/artisan hls:process ".$post_id." > /dev/null 2>&1 & ");
        return 0;
    }

    public function aws_elemental_media_convert_start_job($s3_file_path, 
                                                            $file_name, 
                                                            $video_meta, 
                                                            $output_key_prefix  = "temp/", 
                                                            $output_filename = "",
                                                            $exculded_media_size = [],
                                                            $mc_params = [] ) { 

     


        extract($mc_params, EXTR_PREFIX_ALL, "mc");


        $codec_quality_tuning_level = isset($mc_codec_quality_tuning_level) ? $mc_codec_quality_tuning_level : "MULTI_PASS_HQ";
        $codec_rate_control_mode = isset($mc_rate_control_mode) ? $mc_rate_control_mode : "QVBR";

        $file_name_pieces = explode(".", $file_name);

        array_splice($file_name_pieces, count($file_name_pieces) - 1);

        $raw_file_name      = str_replace("_mc", "", implode(",", $file_name_pieces));

        $width  = $video_meta->width;
        $height = $video_meta->height;

        $orginal_bitrate = (int) $video_meta->bit_rate;
        
        //If enable, use reference B frames for GOP structures that have B frames > 1.
        $gop_b_reference    = $video_meta->has_b_frames > 0 ? "ENABLED":"DISABLED";

        // $frame_rate         = (float) eval( 'return '. $video_meta->r_frame_rate.';' );
        // if($frame_rate < 15 && $frame_rate > 60 ) $frame_rate = (float) eval( 'return '. $video_meta->avg_frame_rate.';' );

        $frame_rate = (float) eval( 'return '. $video_meta->avg_frame_rate.';' ); 


        $frame_rate_parts = explode(".", $frame_rate);
        if(  $frame_rate > 0 && count($frame_rate_parts) > 1 && ( (int)$frame_rate_parts[1] ) == 0 ) {
            $frame_rate = (int) $frame_rate;
        }
        else 
            $frame_rate = (int) $frame_rate;

   

        if($video_meta->has_b_frames > 0) 
            $b_no_ref_frame  = (int) ( $video_meta->refs > 0 ) ? $video_meta->refs: 1; 
        else 
            $b_no_ref_frame  = (int)  ( $video_meta->refs > 0 ) ? $video_meta->refs: 1; 


        $frame_numerator = $frame_denominator = 0;
        
        if(strpos($video_meta->r_frame_rate, "/") !== FALSE) {
            list($frame_numerator, $frame_denominator) = explode("/", trim($video_meta->r_frame_rate));
        }
        else {
            $frame_numerator = (strlen( (int) $frame_rate ) == 2) ? $frame_rate * 1000 :  $frame_numerator ;
            $frame_denominator = 1000;
        }

        //overiding
        if(strpos($video_meta->avg_frame_rate, "/") !== FALSE) {
            list($frame_numerator, $frame_denominator) = explode("/", trim($video_meta->avg_frame_rate));
        }
        else {
            $frame_numerator = (strlen( (int) $frame_rate ) == 2) ? $frame_rate * 1000 :  $frame_numerator ;
            $frame_denominator = 1000;
        }
        
        $rotation       = (int)$video_meta->rotate;
        $aspect_ratio   =  $width / $height;
        
        $Outputs = [];

        $inputs = [
            "AudioSelectors" => [
                "Audio Selector 1" => [
                    "Offset" => 0,
                    "DefaultSelection" => "DEFAULT",
                    "ProgramSelection" => 1
                ]
            ],
            "VideoSelector" => [
                "ColorSpace" => "FOLLOW",
                "Rotate" => "AUTO"
            ],
            "FilterEnable" => "AUTO",
            "PsiControl" => "USE_PSI",
            "FilterStrength" => 0,
            "DeblockFilter" => "ENABLED",
            "DenoiseFilter" => "ENABLED",
            "InputScanType" => "AUTO",
            "TimecodeSource"=> "ZEROBASED",
            "FileInput" => "s3://moda-staging/".$s3_file_path 
        ];

        $container_settings = [
            "Container" => "M3U8",
            "M3u8Settings" => [
                "AudioFramesPerPes" => 4,
                "PcrControl" => "PCR_EVERY_PES_PACKET",
                "PmtPid" => 480,
                "PrivateMetadataPid"=> 503,
                "ProgramNumber"=> 1,
                "PatInterval"=> 0,
                "PmtInterval"=> 0,
                "Scte35Source"=> "NONE",
                "NielsenId3"=> "NONE",
                "TimedMetadata"=> "NONE",
                "VideoPid"=> 481,
                "AudioPids" => [
                    482,
                    483,
                    484,
                    485,
                    486,
                    487,
                    488,
                    489,
                    490,
                    491,
                    492
                ],
                "AudioDuration" => "DEFAULT_CODEC_DURATION"
            ]
        ];

        $audio_descriptons = [
            "AudioTypeControl" => "FOLLOW_INPUT",
            "CodecSettings" => [

                "Codec" => "AAC",
                "AacSettings" => [
                    "AudioDescriptionBroadcasterMix" => "NORMAL",
                    "Bitrate" => 96000,
                    "RateControlMode" => "CBR",
                    "CodecProfile" => "LC",
                    "CodingMode" => "CODING_MODE_2_0",
                    "RawFormat" => "NONE",
                    "SampleRate" => 48000,
                    "Specification" => "MPEG4"
               ]   
            ],
            "LanguageCodeControl" => "FOLLOW_INPUT"
        ];

        $output_hls_settings = [
            "AudioGroupId" => "program_audio",
            "AudioOnlyContainer" => "AUTOMATIC",
            "IFrameOnlyManifest" => "EXCLUDE"
        ];

        

        if( !in_array("1080p", $exculded_media_size) && ( ($width * $height) >= (1920 * 1080) || ($width * $height) >= (1920 * 1080)/2 ) ) {

            
            list($new_width, $new_height)   = $this->calc_diminesion($width, $height, 1920, 1080, $rotation);

            $current_media_size_bitrate = 5000000;

            $video_description_settings     = $this->get_hls_video_description([

                                                    "width" => $new_width, 
                                                    "height" => $new_height, 
                                                    //"bitrate" => $current_media_size_bitrate, 

                                                    "rate_control_mode" => $codec_rate_control_mode,
                                                    "quality_tuning_level" => $codec_quality_tuning_level,
                                                    "qvbr_quality_level" => 10,
                                                    "max_bitrate" => round(($current_media_size_bitrate * 120)/100),
                                                    
                                                    "hrd_buffer_size" => round(($current_media_size_bitrate * 150)/100),
                                                    "hrd_buffer_fill_percentage" => 90,

                                                    "gop_size" => 90,
                                                    "gop_closed_cadence" => 1,
                                                    "gop_b_reference" => $gop_b_reference,
                                                    "b_refrence_frame" => $video_meta->has_b_frames,
                                                    "num_ref_frames" => $b_no_ref_frame,
                                                    "scene_change_detect" => "TRANSITION_DETECTION", //for qvbr 


                                                    "profile" => "HIGH",
                                                    "codec_level" => "AUTO", 

                                                    // "spatial_adp_quant" => "ENABLED",
                                                    // "temp_adp_quant" => "ENABLED", 
                                                    // "flicker_adp_quant" => "DISABLED", 
                                                    // "adaptive_quantization" => "HIGH",

                                                    "frame_rate_numerator" => ($frame_rate > 0 ) ? $frame_numerator : 30000,
                                                    "frame_rate_denominator" => ($frame_rate > 0) ? $frame_denominator : 1001,
                                                ]);

            // $video_description_settings     = $this->get_hls_video_description(["width" => $new_width, 
            //                                                                 "height" => $new_height, 
            //                                                                 "bitrate" => 8500000, 
            //                                                                 "codec_level" => "LEVEL_4_2", 
            //                                                                 "hrd_buffer_size" => 12750000,
            //                                                                 "hrd_buffer_fill_percentage" => 90,
            //                                                                 //"b_refrence_frame" => 1,
            //                                                                 "profile" => "HIGH",
            //                                                                 // "spatial_adp_quant" => "ENABLED",
            //                                                                 // "temp_adp_quant" => "ENABLED", 
            //                                                                 // "flicker_adp_quant" => "DISABLED", 
            //                                                                 // "adaptive_quantization" => "HIGH"
            //                                                                 ]);

            // $video_description_settings["VideoPreprocessors"]["Deinterlacer"] = ["Algorithm" => "INTERPOLATE",
            //                                                                      "Mode" => "DEINTERLACE",
            //                                                                      "Control" => "NORMAL"];
            $container_1080p = [
                "ContainerSettings" => $container_settings,
                "VideoDescription" =>  $video_description_settings,
                "AudioDescriptions" => [ $audio_descriptons ],
                "OutputSettings" => [
                    "HlsSettings" => $output_hls_settings 
                ],
                "NameModifier" => "1080p"
            ];

            $outputs[] =  $container_1080p;
        }

        if( !in_array("720p", $exculded_media_size) && ( ($width * $height) >= (1280 * 720) || ($width * $height) >= (1280 * 720)/2 ) ) {

            list($new_width, $new_height)   = $this->calc_diminesion($width, $height, 1280, 720, $rotation);

            $current_media_size_bitrate = 3000000;

            $video_description_settings     = $this->get_hls_video_description([

                                                    "width" => $new_width, 
                                                    "height" => $new_height, 
                                                    //"bitrate" => $current_media_size_bitrate, 

                                                    "rate_control_mode" => $codec_rate_control_mode,
                                                    "quality_tuning_level" => $codec_quality_tuning_level,
                                                    "qvbr_quality_level" => 9,
                                                    "max_bitrate" => round(($current_media_size_bitrate * 120)/100),
                                                    
                                                    "hrd_buffer_size" => round(($current_media_size_bitrate * 150)/100),
                                                    "hrd_buffer_fill_percentage" => 90,

                                                    "gop_size" => 90,
                                                    "gop_closed_cadence" => 1,
                                                    "gop_b_reference" => $gop_b_reference,
                                                    "b_refrence_frame" => $video_meta->has_b_frames,
                                                    "num_ref_frames" => $b_no_ref_frame,
                                                    "scene_change_detect" => "TRANSITION_DETECTION", //for qvbr 


                                                    "profile" => "HIGH",
                                                    "codec_level" => "AUTO", 

                                                    // "spatial_adp_quant" => "ENABLED",
                                                    // "temp_adp_quant" => "ENABLED", 
                                                    // "flicker_adp_quant" => "DISABLED", 
                                                    // "adaptive_quantization" => "HIGH",


                                                    "frame_rate_numerator" => ($frame_rate > 0 ) ? $frame_numerator : 30000,
                                                    "frame_rate_denominator" => ($frame_rate > 0) ? $frame_denominator : 1001,
                                                ]);

            // $video_description_settings     = $this->get_hls_video_description(["width" => $new_width, 
            //                                                                 "height" => $new_height, 
            //                                                                 "bitrate" => 3500000, 
            //                                                                 "codec_level" => "AUTO", 
            //                                                                 // "hrd_buffer_size" => 5250000,
            //                                                                 // "hrd_buffer_fill_percentage" => 90,
            //                                                                 // "b_refrence_frame" => 1,
            //                                                                 "profile" => "HIGH",
            //                                                                 // "spatial_adp_quant" => "ENABLED",
            //                                                                 // "temp_adp_quant" => "ENABLED", 
            //                                                                 // "flicker_adp_quant" => "DISABLED", 
            //                                                                 // "adaptive_quantization" => "HIGH"
            //                                                                 "frame_rate_control" => "SPECIFIED",
            //                                                                 "frame_rate_numerator" => 30000,
            //                                                                 "frame_rate_denominator" => 1001,
            //                                                                 ]);

            // $video_description_settings["VideoPreprocessors"]["Deinterlacer"] = ["Algorithm" => "INTERPOLATE",
            //                                                                     "Mode" => "DEINTERLACE",
            //                                                                     "Control" => "NORMAL"];
            
            $container_720p = [
                "ContainerSettings" => $container_settings,
                "VideoDescription" =>  $video_description_settings,
                "AudioDescriptions" => [ $audio_descriptons ],
                "OutputSettings" => [
                    "HlsSettings" => $output_hls_settings 
                ],
                "NameModifier" => "720p"
            ];

            $outputs[] =  $container_720p;
        }

        if( !in_array("540p", $exculded_media_size) && ( ($width * $height) >= (960 * 540) || ($width * $height) >= (960 * 540)/2 ) ) {

            list($new_width, $new_height)   = $this->calc_diminesion($width, $height, 960, 540, $rotation);
            
            $current_media_size_bitrate = 1500000;

            $video_description_settings     = $this->get_hls_video_description([

                                                    "width" => $new_width, 
                                                    "height" => $new_height, 
                                                    //"bitrate" => $current_media_size_bitrate, 

                                                    "rate_control_mode" => $codec_rate_control_mode,
                                                    "quality_tuning_level" => $codec_quality_tuning_level,
                                                    "qvbr_quality_level" => 8,
                                                    "max_bitrate" => round(($current_media_size_bitrate * 120)/100),
                                                    
                                                    "hrd_buffer_size" => round(($current_media_size_bitrate * 150)/100),
                                                    "hrd_buffer_fill_percentage" => 90,

                                                    "gop_size" => 90,
                                                    "gop_closed_cadence" => 1,
                                                    "gop_b_reference" => $gop_b_reference,
                                                    "b_refrence_frame" => $video_meta->has_b_frames,
                                                    "num_ref_frames" => $b_no_ref_frame,
                                                    "scene_change_detect" => "TRANSITION_DETECTION", //for qvbr 


                                                    "profile" => "HIGH",
                                                    "codec_level" => "AUTO", 

                                                    // "spatial_adp_quant" => "ENABLED",
                                                    // "temp_adp_quant" => "ENABLED", 
                                                    // "flicker_adp_quant" => "DISABLED", 
                                                    // "adaptive_quantization" => "HIGH",


                                                    "frame_rate_numerator" => ($frame_rate > 0 ) ? $frame_numerator : 30000,
                                                    "frame_rate_denominator" => ($frame_rate > 0) ? $frame_denominator : 1001,
                                                ]);

            // $video_description_settings     = $this->get_hls_video_description(["width" => $new_width, 
            //                                                                 "height" => $new_height, 
            //                                                                 "bitrate" => 2500000, 
            //                                                                 "codec_level" => "AUTO", 
            //                                                                 // "hrd_buffer_size" => 5250000,
            //                                                                 // "hrd_buffer_fill_percentage" => 90,
            //                                                                 // "b_refrence_frame" => 1,
            //                                                                 "profile" => "HIGH",
            //                                                                 // "spatial_adp_quant" => "ENABLED",
            //                                                                 // "temp_adp_quant" => "ENABLED", 
            //                                                                 // "flicker_adp_quant" => "DISABLED", 
            //                                                                 // "adaptive_quantization" => "HIGH"
            //                                                                 "frame_rate_control" => "SPECIFIED",
            //                                                                 "frame_rate_numerator" => 30000,
            //                                                                 "frame_rate_denominator" => 1001,
            //                                                                 ]);

            // $video_description_settings["VideoPreprocessors"]["Deinterlacer"] = ["Algorithm" => "INTERPOLATE",
            //                                                                     "Mode" => "DEINTERLACE",
            //                                                                     "Control" => "NORMAL"];
            
            $container_540p = [
                "ContainerSettings" => $container_settings,
                "VideoDescription" =>  $video_description_settings,
                "AudioDescriptions" => [ $audio_descriptons ],
                "OutputSettings" => [
                    "HlsSettings" => $output_hls_settings 
                ],
                "NameModifier" => "540p"
            ];

            $outputs[] =  $container_540p;
        }

        if( !in_array("480p", $exculded_media_size) && ( ($width * $height) >= (854 * 480) || ($width * $height) >= (854 * 480)/2 ) ) {

            list($new_width, $new_height)   = $this->calc_diminesion($width, $height, 854, 480, $rotation);

            $current_media_size_bitrate     = 1200000;

            $video_description_settings     = $this->get_hls_video_description([

                                                            "width" => $new_width, 
                                                            "height" => $new_height, 
                                                            //"bitrate" => $current_media_size_bitrate, 

                                                            "rate_control_mode" => $codec_rate_control_mode,
                                                            "quality_tuning_level" => $codec_quality_tuning_level,
                                                            "qvbr_quality_level" => 7,
                                                            "max_bitrate" => round(($current_media_size_bitrate * 120)/100),
                                                            
                                                            "hrd_buffer_size" => round(($current_media_size_bitrate * 150)/100),
                                                            "hrd_buffer_fill_percentage" => 90,

                                                            "gop_size" => 90,
                                                            "gop_closed_cadence" => 1,
                                                            "gop_b_reference" => $gop_b_reference,
                                                            "b_refrence_frame" => $video_meta->has_b_frames,
                                                            "num_ref_frames" => $b_no_ref_frame,
                                                            "scene_change_detect" => "TRANSITION_DETECTION", //for qvbr 


                                                            "profile" => "HIGH",
                                                            "codec_level" => "AUTO", 

                                                            // "spatial_adp_quant" => "ENABLED",
                                                            // "temp_adp_quant" => "ENABLED", 
                                                            // "flicker_adp_quant" => "DISABLED", 
                                                            // "adaptive_quantization" => "HIGH",


                                                            "frame_rate_numerator" => ($frame_rate > 0) ? $frame_numerator : 30000,
                                                            "frame_rate_denominator" => ($frame_rate > 0) ? $frame_denominator : 1001,
            ]);

            // $video_description_settings     = $this->get_hls_video_description(["width" => $new_width, 
            //                                                                 "height" => $new_height, 
            //                                                                 "bitrate" => 2000000,
            //                                                                 "rate_control_mode" => "VBR",
            //                                                                 "codec_level" => "AUTO", 
            //                                                                 // "hrd_buffer_size" => 900000,
            //                                                                 // "hrd_buffer_fill_percentage" => 90,
            //                                                                 "b_refrence_frame" => 1,
            //                                                                 "profile" => "HIGH",
            //                                                                 // "spatial_adp_quant" => "ENABLED",
            //                                                                 // "temp_adp_quant" => "ENABLED", 
            //                                                                 // "flicker_adp_quant" => "DISABLED", 
            //                                                                 // "adaptive_quantization" => "HIGH",
            //                                                                 "frame_rate_control" => "SPECIFIED",
            //                                                                 "frame_rate_numerator" => 24000,
            //                                                                 "frame_rate_denominator" => 1001,
            //                                                                 ]);

            // $video_description_settings["VideoPreprocessors"]["Deinterlacer"] = ["Algorithm" => "INTERPOLATE",
            //                                                                 "Mode" => "DEINTERLACE",
            //                                                                 "Control" => "NORMAL"];

            $container_480p = [
                "ContainerSettings" => $container_settings,
                "VideoDescription" =>  $video_description_settings,
                "AudioDescriptions" => [ $audio_descriptons ],
                "OutputSettings" => [
                    "HlsSettings" => $output_hls_settings 
                ],
                "NameModifier" => "480p"
            ];

            $outputs[] =  $container_480p;
        }

        if( !in_array("360p", $exculded_media_size) && ( ($width * $height) >= (640 * 360) || ($width * $height) >= (640 * 360)/2) ) {

            list($new_width, $new_height)   = $this->calc_diminesion($width, $height, 640, 360, $rotation);

            $current_media_size_bitrate     = 800000;

            $video_description_settings     = $this->get_hls_video_description([

                                                            "width" => $new_width, 
                                                            "height" => $new_height, 
                                                            //"bitrate" => $current_media_size_bitrate, 

                                                            "rate_control_mode" => $codec_rate_control_mode,
                                                            "quality_tuning_level" => $codec_quality_tuning_level,
                                                            "qvbr_quality_level" => 7,
                                                            "max_bitrate" => round(($current_media_size_bitrate * 120)/100),
                                                            
                                                            "hrd_buffer_size" => round(($current_media_size_bitrate * 150)/100),
                                                            "hrd_buffer_fill_percentage" => 90,

                                                            "gop_size" => 90,
                                                            "gop_closed_cadence" => 1,
                                                            "gop_b_reference" => $gop_b_reference,
                                                            "b_refrence_frame" => $video_meta->has_b_frames,
                                                            "num_ref_frames" => $b_no_ref_frame,
                                                            "scene_change_detect" => "TRANSITION_DETECTION", //for qvbr 


                                                            "profile" => "MAIN",
                                                            "codec_level" => "AUTO", 

                                                            // "spatial_adp_quant" => "ENABLED",
                                                            // "temp_adp_quant" => "ENABLED", 
                                                            // "flicker_adp_quant" => "DISABLED", 
                                                            // "adaptive_quantization" => "HIGH",


                                                            "frame_rate_numerator" => ($frame_rate > 0) ? $frame_numerator : 30000,
                                                            "frame_rate_denominator" => ($frame_rate > 0) ? $frame_denominator : 1001,
            ]);

            // $video_description_settings     = $this->get_hls_video_description(["width" => $new_width, 
            //                                                                 "height" => $new_height, 
            //                                                                 "bitrate" => 1500000, 
            //                                                                 "rate_control_mode" => "VBR",
            //                                                                 "codec_level" => "AUTO", 
            //                                                                 // "hrd_buffer_size" => 300000,
            //                                                                 // "hrd_buffer_fill_percentage" => 90,
            //                                                                 "b_refrence_frame" => 1,
            //                                                                 "profile" => "MAIN",
            //                                                                 // "spatial_adp_quant" => "ENABLED",
            //                                                                 // "temp_adp_quant" => "ENABLED", 
            //                                                                 // "flicker_adp_quant" => "DISABLED", 
            //                                                                 // "adaptive_quantization" => "HIGH",
            //                                                                 "frame_rate_control" => "SPECIFIED",
            //                                                                 "frame_rate_numerator" => 24000,
            //                                                                 "frame_rate_denominator" => 1001,
            //                                                                 ]);

            // $video_description_settings["VideoPreprocessors"]["Deinterlacer"] = ["Algorithm" => "INTERPOLATE",
            //                                                                 "Mode" => "DEINTERLACE",
            //                                                                 "Control" => "NORMAL"];
            $container_360p = [
                "ContainerSettings" => $container_settings,
                "VideoDescription" =>  $video_description_settings,
                "AudioDescriptions" => [ $audio_descriptons ],
                "OutputSettings" => [
                    "HlsSettings" => $output_hls_settings 
                ],
                "NameModifier" => "360p"
            ];

            $outputs[] =  $container_360p;
        }

        if( !in_array("270p", $exculded_media_size) && ( ($width * $height) >= (480 * 270) || ($width * $height) >= (480 * 270)/2) ) {

            list($new_width, $new_height)   = $this->calc_diminesion($width, $height, 480, 270, $rotation);

            $current_media_size_bitrate     = 400000;

            $video_description_settings     = $this->get_hls_video_description([

                                                            "width" => $new_width, 
                                                            "height" => $new_height, 
                                                            //"bitrate" => $current_media_size_bitrate, 

                                                            "rate_control_mode" => $codec_rate_control_mode,
                                                            "quality_tuning_level" => $codec_quality_tuning_level,
                                                            "qvbr_quality_level" => 7,
                                                            "max_bitrate" => round(($current_media_size_bitrate * 120)/100),
                                                            
                                                            "hrd_buffer_size" => round(($current_media_size_bitrate * 150)/100),
                                                            "hrd_buffer_fill_percentage" => 90,

                                                            "gop_size" => 90,
                                                            "gop_closed_cadence" => 1,
                                                            "gop_b_reference" => $gop_b_reference,
                                                            "b_refrence_frame" => $video_meta->has_b_frames,
                                                            "num_ref_frames" => $b_no_ref_frame,
                                                            "scene_change_detect" => "TRANSITION_DETECTION", //for qvbr 

                                                            "profile" => "MAIN",
                                                            "codec_level" => "AUTO", 

                                                            // "spatial_adp_quant" => "ENABLED",
                                                            // "temp_adp_quant" => "ENABLED", 
                                                            // "flicker_adp_quant" => "DISABLED", 
                                                            // "adaptive_quantization" => "HIGH",

                                                            // "frame_rate_numerator" => ($frame_rate <=0) ? $frame_numerator : 30000,
                                                            // "frame_rate_denominator" => ($frame_rate <=0) ? $frame_denominator : 1001,
                                                            "frame_rate_numerator" => ($frame_rate > 0) ? $frame_numerator : 30000,
                                                            "frame_rate_denominator" => ($frame_rate > 0) ? $frame_denominator : 1001,
            ]);

            // $video_description_settings     = $this->get_hls_video_description(["width" => $new_width, 
            //                                                                 "height" => $new_height, 
            //                                                                 "bitrate" => 1000000, 
            //                                                                 "codec_level" => "AUTO", 
            //                                                                 // "hrd_buffer_size" => 600000,
            //                                                                 // "hrd_buffer_fill_percentage" => 90,
            //                                                                 // "b_refrence_frame" => 1,
            //                                                                 "profile" => "MAIN",
            //                                                                 // "spatial_adp_quant" => "ENABLED",
            //                                                                 // "temp_adp_quant" => "ENABLED", 
            //                                                                 // "flicker_adp_quant" => "DISABLED",
            //                                                                 // "gop_size" => 45,  
            //                                                                 // "adaptive_quantization" => "HIGH",
            //                                                                 "frame_rate_control" => "SPECIFIED",
            //                                                                 "frame_rate_numerator" => 24000,
            //                                                                 "frame_rate_denominator" => 1001,
            //                                                                 ]);

            
            $container_240p = [
                "ContainerSettings" => $container_settings,
                "VideoDescription" => $video_description_settings,
                "AudioDescriptions" => [ $audio_descriptons ],
                "OutputSettings" => [
                    "HlsSettings" => $output_hls_settings 
                ],
                "NameModifier" => "240p"
            ];

            $outputs[] =  $container_240p;
        }
        
        //Custom media size from the orginal video params
        // if( !in_array("customOrginal", $exculded_media_size) && ( ($width * $height) < (1024 * 576) ) ) { //width and height checking removed on the rquest of jinto
        if( !in_array("customOrginal", $exculded_media_size)  ) {

            list($new_width, $new_height)   = $this->calc_diminesion($width, $height, $width, $height, $rotation);

            $current_media_size_bitrate = ($orginal_bitrate > 0) ? $orginal_bitrate : round(($width * $height * $frame_rate * 0.1)/1000) ;

            $video_description_settings     = $this->get_hls_video_description([

                                                    "width" => $new_width, 
                                                    "height" => $new_height, 
                                                    //"bitrate" => $current_media_size_bitrate, 

                                                    "rate_control_mode" => $codec_rate_control_mode,
                                                    "quality_tuning_level" => $codec_quality_tuning_level,

                                                    "qvbr_quality_level" => 7,
                                                    "max_bitrate" => round(($current_media_size_bitrate * 120)/100),
                                                    
                                                    "hrd_buffer_size" => round(($current_media_size_bitrate * 150)/100),
                                                    "hrd_buffer_fill_percentage" => 90,

                                                    "gop_size" => 90,
                                                    "gop_closed_cadence" => 1,
                                                    "gop_b_reference" => $gop_b_reference,
                                                    "b_refrence_frame" => $video_meta->has_b_frames,
                                                    "num_ref_frames" => $b_no_ref_frame,
                                                    "scene_change_detect" => "TRANSITION_DETECTION", //for qvbr 


                                                    "profile" => "HIGH",
                                                    "codec_level" => "AUTO", 

                                                    // "spatial_adp_quant" => "ENABLED",
                                                    // "temp_adp_quant" => "ENABLED", 
                                                    // "flicker_adp_quant" => "DISABLED", 
                                                    // "adaptive_quantization" => "HIGH",


                                                    "frame_rate_numerator" => ($frame_rate > 0 ) ? $frame_numerator : 30000,
                                                    "frame_rate_denominator" => ($frame_rate > 0) ? $frame_denominator : 1001,
                                                ]);
            $container_custom = [
                "ContainerSettings" => $container_settings,
                "VideoDescription" => $video_description_settings,
                "AudioDescriptions" => [ $audio_descriptons ],
                "OutputSettings" => [
                    "HlsSettings" => $output_hls_settings 
                ],
                "NameModifier" => "customOrginal"
            ];

            $outputs[] =  $container_custom;

        }

        if(count($outputs) == 0) {

            list($new_width, $new_height) = $this->calc_diminesion($width, $height, 256, 144, $rotation);

            $video_description_settings = $this->get_hls_video_description(["width" => $new_width, 
                                                                            "height" => $new_height, 
                                                                            "bitrate" => 400000, 
                                                                            "codec_level" => "AUTO", 
                                                                            "profile" => "MAIN"]);


            $container_144p = [
                "ContainerSettings" => $container_settings,
                "VideoDescription" => $video_description_settings,
                "AudioDescriptions" => [ $audio_descriptons ],
                "OutputSettings" => [
                    "HlsSettings" => $output_hls_settings 
                ],
                "NameModifier" => "144p"
            ];

            $outputs[] =  $container_144p;
        }

        $job_setting = [    
            "TimecodeConfig" => [
                "Source" => "ZEROBASED"
            ],
            "OutputGroups" => [
                [
                    "Name" => "Apple HLS",
                    "Outputs" => $outputs,
                    "OutputGroupSettings" => [
                        "Type" => "HLS_GROUP_SETTINGS",
                        "HlsGroupSettings" => [
                            "ManifestDurationFormat" => "INTEGER",
                            "SegmentLength" => 3,
                            "TimedMetadataId3Period" => 10,
                            "CaptionLanguageSetting" => "OMIT",
                            "Destination" => "s3://moda-staging/".$output_key_prefix."".$raw_file_name."/".$output_filename,
                            "DestinationSettings" => [
                                "S3Settings" => [
                                    "AccessControl" => [
                                        "CannedAcl" => "PUBLIC_READ"
                                    ]
                                ]
                            ],
                            "TimedMetadataId3Frame" => "PRIV",
                            "CodecSpecification" => "RFC_4281",
                            "OutputSelection" => "MANIFESTS_AND_SEGMENTS",
                            "ProgramDateTimePeriod" => 600,
                            "MinSegmentLength" => 0,
                            "MinFinalSegmentLength" =>  0,
                            "DirectoryStructure" => "SINGLE_DIRECTORY",
                            "ProgramDateTime" => "EXCLUDE",
                            "SegmentControl" => "SEGMENTED_FILES",
                            "ManifestCompression" => "NONE",
                            "ClientCache" => "ENABLED",
                            "AudioOnlyHeader" => "INCLUDE",
                            "StreamInfResolution" => "INCLUDE"
                        ]
                    ]
                ],
            ],
            "Inputs" => [ $inputs ], 
            "AdAvailOffset" => 0,
            // "AccelerationSettings" => [
            //     "Mode" => "ENABLED"//"DISABLED"
            // ],
        ];

        print_r($job_setting);
        try {
            $result = $this->media_convert_client->createJob([
                "Role" =>  "arn:aws:iam::706291862771:role/service-role/MediaConvert_Default_Role",
                "Settings" => $job_setting, //JobSettings structure
                "Queue" => "arn:aws:mediaconvert:ap-southeast-1:706291862771:queues/Default",
                "UserMetadata" => [
                    "Customer" => "Amazon"
                ],
                "StatusUpdateInterval" => isset($mc_status_interval) ? $mc_status_interval : "SECONDS_30",
                "Priority" => 0,
                "AccelerationSettings" => [
                    "Mode" => "DISABLED"
                ],
            ]);

            $result_job = $result["Job"];
            return $result_job["Id"];
        } 
        catch (AwsException $e) {
            echo $e->getMessage();
            return "";
        }
        

    }

    private function get_hls_video_description($code_params = []) {
        extract($code_params);

        $h264_settings = [
            "InterlaceMode" => "PROGRESSIVE",
            "ScanTypeConversionMode" => "INTERLACED",
            "NumberReferenceFrames" => isset($num_ref_frames) ? $num_ref_frames : 3,
            "Syntax" => "DEFAULT",
            "Softness" => 0,
            "GopClosedCadence" => isset($gop_closed_cadence) ? $gop_closed_cadence : 1,
            "GopSize" =>  isset($gop_size) ? $gop_size : 90,
            "Slices" => 1,  
            "GopBReference" => isset($gop_b_reference) ? $gop_b_reference : "ENABLED", //[ENABLED|DISABLED],
            "SlowPal" => "DISABLED",
            "EntropyEncoding" => "CABAC",
            //"Bitrate" => isset($bitrate) ? $bitrate : 0, //8500000,
            //"FramerateControl" => isset($frame_rate_contol) ? $frame_rate_control : "INITIALIZE_FROM_SOURCE",
            "RateControlMode" =>  isset($rate_control_mode) ? $rate_control_mode :"CBR",
            "CodecProfile" => isset($profile) ? $profile : "MAIN",
            "Telecine" => "NONE",
            "MinIInterval" => isset($min_it_interval) ? $min_it_interval : 0,
            "AdaptiveQuantization" => isset($adaptive_quantization) ? $adaptive_quantization : "AUTO",
            "CodecLevel" => isset($codec_level) ? $codec_level : "AUTO",
            "FieldEncoding" => "PAFF",
            "SceneChangeDetect" => isset($scene_change_detect) ? $scene_change_detect : "ENABLED"  , //[DISABLED|ENABLED|TRANSITION_DETECTION]
            "QualityTuningLevel" => isset($quality_tuning_level) ? $quality_tuning_level : "MULTI_PASS_HQ_HQ"  , //"MULTI_PASS_HQ_HQ",
            "FramerateConversionAlgorithm" => "FRAMEFORMER", //[DUPLICATE_DROP|INTERPOLATE|FRAMEFORMER]
            "UnregisteredSeiTimecode" => "DISABLED",
            "GopSizeUnits" => "FRAMES",
            "ParControl" => "INITIALIZE_FROM_SOURCE",
            "NumberBFramesBetweenReferenceFrames" => isset($b_refrence_frame) ? $b_refrence_frame : 2,
            "RepeatPps" => "DISABLED",
            "DynamicSubGop" => "ADAPTIVE" //[ADAPTIVE|STATIC]
        ];

        //https://docs.amazonaws.cn/en_us/mediaconvert/latest/apireference/presets.html#presets-prop-videocodecsettings-h264settings

        if(isset($bitrate) && $bitrate > 0) 
            $h264_settings["Bitrate"] = $bitrate;

        if( $rate_control_mode == "QVBR" )  {
            $h264_settings["QvbrSettings"] = [
                "QvbrQualityLevel" =>  isset($qvbr_quality_level) ? $qvbr_quality_level :7,
                "QvbrQualityLevelFineTune"=>  isset($qvbr_quality_level_ftune) ? $qvbr_quality_level_ftune : 0
            ];
        }

        if( $rate_control_mode == "QVBR" || $rate_control_mode == "VBR" )  {
            $h264_settings["MaxBitrate"] = isset($max_bitrate) ? $max_bitrate : round( ($bitrate * 125 /100))  + $bitrate;
        }

        if(isset($hrd_buffer_size)) 
            $h264_settings["HrdBufferSize"] = $hrd_buffer_size;

        if(isset($hrd_buffer_fill_percentage)) 
            $h264_settings["HrdBufferInitialFillPercentage"] = $hrd_buffer_fill_percentage;

        if(isset($spatial_adp_quant)) 
            $h264_settings["SpatialAdaptiveQuantization"] = $spatial_adp_quant;

        if(isset($temp_adp_quant)) 
            $h264_settings["TemporalAdaptiveQuantization"] = $temp_adp_quant;

        if(isset($flicker_adp_quant)) 
            $h264_settings["FlickerAdaptiveQuantization"] = $flicker_adp_quant;

        if(isset($frame_rate_numerator) && $frame_rate_numerator > 0) 
            $h264_settings["FramerateNumerator"]    = $frame_rate_numerator;

        if(isset($frame_rate_denominator) && $frame_rate_denominator > 0) 
            $h264_settings["FramerateDenominator"]  = $frame_rate_denominator;

        if( (isset($frame_rate_numerator) && $frame_rate_numerator > 0) 
            && (isset($frame_rate_denominator) && $frame_rate_denominator > 0) ) {
            $h264_settings["FramerateControl"]  = "SPECIFIED";
        }
        else {
            $h264_settings["FramerateControl"]  = "INITIALIZE_FROM_SOURCE";
        }

        return ["Width"=> isset($width) ? $width : 0,
                "ScalingBehavior" => "STRETCH_TO_OUTPUT",//DEFAULT",
                "Height"=> isset($height) ? $height : 0,
                "TimecodeInsertion"=> "DISABLED",
                "AntiAlias"=> "ENABLED",
                "Sharpness"=> isset($sharpenss) ? $sharpenss : 50,
                "CodecSettings"=> [
                    "Codec" => "H_264",
                    "H264Settings" => $h264_settings
                ],
                "AfdSignaling" => "NONE",
                "DropFrameTimecode" => "ENABLED",
                "RespondToAfd" => "NONE",
                "ColorMetadata" => "IGNORE"];
    }

    private function calc_diminesion($width, $height, $new_width, $new_height, $rotation) {
        if($width >= $height) {
            $adj_width  = $new_width;
            $adj_height = floor( ($height / $width) * $new_width);
        }
        else {
            $adj_height  = $new_height;
            $adj_width = floor( ($width / $height) * $new_height);
        }

        if( $rotation == 90 || $rotation == -90 ) 
            return [ (($adj_height%2) == 0) ? $adj_height : $adj_height + 1, (($adj_width%2) == 0) ? $adj_width : $adj_width + 1]; 
        else
            return [ (($adj_width%2) == 0) ? $adj_width : $adj_width + 1 , (($adj_height%2) == 0) ? $adj_height : $adj_height + 1]; 
            
    }


}

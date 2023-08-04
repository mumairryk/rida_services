<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;

use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;
use Kreait\Firebase\Contract\Database;

class ChangeMobileController extends Controller
{
   
   public function upload_data(){
    $dogs = 'AFFENPINSCHER_ AKITA_ ALASKAN M ALAM UTE_ AMERICAN WIREHAIRD_ ARGENTINE DOGO_ AUSTRALIAN CATTLE DOG_ BASENJI_ BEAGLE_ BELGIAN TERVERUEN_ BERNESE MOUNTIAN DOG_ BICHON FISE_ BOLOGNESE_ BORZOI_ BOUVIER DES FLANDERS_ BOXER_ BRIARD_ BULLDOG, AMERICAN_ BULLDOG, ENGLISH_ BULLDOG, FRENCH_ CANAAN DOG_ CHIHUAHUA, SHORTHAIR_ CHIHUAHUA, LONGHAIR_ CHIHUAHUA, MEXICAN HAIRLESS_ CHINESE CRESTED_ CHOW CHOW_ COLLIE, BEARDED_ COLLIE, BORDER_ COLLIE, ROUGH_ COLLIE, SMOOTH_ COTON DE TULEIRE_ CORGI, CARDIGAN WELSH_ CORGI, PEMBROKE WELSH_ DACHSHUND, LONGHAIR_ DACHSHUND, MINIATURE_ DACHSHUND, SMOOTH_ DACHSHUND, WIREHAIRED_ DALMATIAN_ DOBERMAN PINSCHER_ DOGUE DE BORDEAUX_ ESKIMO, AMERICAN_ FILA BRASILEIRO_ FOXHOUND, AMERICAN_ FOXHOUND, ENGLISH_ GREAT DANE_ GREAT PYRANESE_ GRIFFON, BRUSSELS_ GRIFFON VENDEEN, PETITE_ BASSETT_ GRIFFON, WIREHAIRED_ POINTING_ HARRIER_ HAVANESE_ HOUND, AFGHAN_ HOUND, BASSET_ HOUND, BLOOD_ HOUND, CATAHOULA_ HOUND, COON_ HOUND, GREY_ HOUND, IBIZAN_ HOUND, IRISH WOLF_ HOUND, ITALIAN GREY_ HOUND, NORWEGIAN ELK_ HOUND, OTTER_ HOUND, PHAROAH_ HOUND, PLOTT_ HOUND, SCOTTISH DEER_ JAPANESE CHIN_ JINDO_ KEESHOUND_ KELPIE_ KOMONDOR_ KUVASZ_ KYUSHU_ LANDSEER_ LEONBERGER_ LHASA APSO_ LOWCHEN_ MALTESE_ MASTIFF, BULL_ MASTIFF, FRENCH_ MASTIFF, NEAPOLITAN_ MASTIFF, OLD ENGLISH_ MASTIFF, S. AFRICAN BOERBOEL_ MINIATURE PINSCHER_ MIXED BREED_ NEWFOUNDLAND_ NEW GUINEA SINGING_ PAPILLION_ PEKINGESE_ PERRO DE PRESA CANARIO_ POINTER_ POINTER, GERMAN_ SHORTHAIRED_ POINTER, GERMAN WIREHAIRED_ POMERANIAN_ POODLE, MINIATURE_ POODLE, STANDARD_ POODLE, TOY_ PORTUGESE WATER DOG_ PUG_ PULI_ RETRIEVER, CHESAPEAKE BAY_ RETRIEVER, CURLY COAT_ RETRIEVER, FLATCOATED_ RETRIEVER, GOLDEN_ RETRIEVER,LABRADAR_ RHODESIAN RIDGEBACK_ ROTTWEILER_ SALUKI_ SAMOYED_ SCHIPPERKE_ SCHNAUZER, GIANT_ SCHNAUZER, MINIATURE_ SCHNAUZER, STANDARD_ SETTER, ENGLISH_ SETTER, GORDON_ SETTER, IRISH_ SHEPHERD, ANATOLIAN_ SHEPHERD, AUSTRALIAN_ SHEPHERD, BELGIAN_ SHEPHERD, GERMAN_ SHAR-PEI_ SHEEPDOG, BELGIAN_ SHEEPDOG, OLD ENGLISH_ SHEEPDOG, SHETLAND_ SHIBA-INU_ SHIH-TZU_ SIBERIAN HUSKY_ SPANIEL, AMERICAN WATER_ SPANIEL, BRITTANY_ SPANIEL, CLUMBER_ SPANIEL, COCKER_ SPANIEL, ENGLISH COCKER_ SPANIEL, ENGLISH SPRINGER_ SPANIEL, ENGLISH TOY_ SPANIEL, FIELD_ SPANIEL, IRISH WATER_ SPANIEL, KING CHARLES_ SPANIEL, SUSSEX_ SPANIEL, TIBETAN_ SPANIEL, WATER_ SPANIEL, WELSH SPRINGER_ SPITZ_ ST. BERNARD_ TERRIER, AIRDALE_ TERRIER, AUSTRALIAN_ TERRIER, BEDLINGTON_ TERRIER, BORDER_ TERRIER, BOSTON BULL_ TERRIER, BULL_ TERRIER, CAIRN_ TERRIER, DANDIE DINMONT_ TERRIER, GERMAN JAGD_ TERRIER, IRISH_ TERRIER, JACK RUSSELL_ TERRIER, KERRY BLUE_ TERRIER, LAKELAND_ TERRIER, MANCHESTER_ TERRIER, NORFOLK_ TERRIER, NORWICH_ TERRIER, PIT BULL_ TERRIER, RAT_ TERRIER, SCOTTISH_ TERRIER, SELYHAM_ TERRIER, SILKY_ TERRIER, SKYE_ TERRIER, SMOOTH FOX_ TERRIER, SOFT COATED WHEATEN_ TERRIER, TIBETAN_ TERRIER, TOY FOX_ TERRIER, TOY MANCHESTER_ TERRIER, WELSH_ TERRIER, WEST HIGHLAND WHITE_ TERRIER, WIREHAIRED FOX_ TERRIER, YORKSHIRE_ TERRIER,AMERICAN STAFFORD SHIRE_ TOSA_ VIZSLA_ WEIMARANER_ WHIPPET_ ';
    $dogs_array = explode('_ ', $dogs);

    $cats = 'ABBYSSINIAN_ AMERICAN BOBTAIL_ AMERICAN CURL_ AMERICAN SHORTHAIR_ BALINESE_ BIRMAN BOMBAY_ BRITISH SHORTHAIR_ BURMESE_ CHARTRAUX_ COLOR-POINT SHORTHAIR_ CORNISH REX_ CYMRIC_ DEVON REX_ DOMESTIC LONGHAIR_ DOMESTIC MEDIUM HAIR_ DOMESTIC SHORTHAIR_ EGYPTIAN MAU_ EXOTIC SHORTHAIR_ HAVANA BROWN_ HIMALAYAN_ JAPANESE BOBTAIL_ JAVANESE_ KO RAT_ LONGHAIRED SCOTTISH FOLD_ MAINE COON_ MANX_ NORWEGIAN FOREST CAT_ OCICAT_ ORIENTAL LONGHAIR_ ORIENTAL SHORTHAIR_ PERSIAN_ RAGDOLL_ RUSSIAN BLUE_ SHORTHAIRED SCOTTISH FOLD_ SIAMESE_ SINGAPURA_ SNOSHOE_ SOMALI_ SPHYNX_ TIFFANY_ TONKINESE_ TURKISH ANGORA_ TUXEDO_ TURKISH VAN_ ';
    $cats_array = explode('_ ', $cats);

        return response()->json([
            'status' => "0",
            'message' => '',
            'error' => (object)'',
        ], 200);

    foreach ($dogs_array as $key => $value) {
        if(trim($value)){
            $breed = new \App\Models\Breeds;
            $breed->active = 1;
            $breed->species = 1; //dogs
            $breed->room_type_id = 2;
            $breed->appoint_time_id = 2;
            $breed->name = trim($value);
            $breed->save();

        }
    }
    foreach ($cats_array as $key => $value) {
        if(trim($value)){
            $breed = new \App\Models\Breeds;
            $breed->active = 1;
            $breed->species = 2; //cats
            $breed->room_type_id = 2;
            $breed->appoint_time_id = 2;
            $breed->name = trim($value);
            $breed->save();

        }
    }

   }
    public function get_mobile_otp(Request $request)
    {


        $rules = [
            'access_token' => 'required',
            'mobile' => 'required',
            'dial_code' => 'required',
        ];
        $messages = [
            'access_token.required' => trans('validation.access_token_required'),
            'mobile.required' => trans('validation.mobile_required'),
            'dial_code.required' => trans('validation.dial_code_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $status = 0;
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
            return response()->json([
                'status' => "0",
                'message' => $message,
                'error' => (object) $errors,
            ], 200);
        }

        if (User::where('phone', $request->mobile)->first()) {
            return response()->json([
                'status' => "0",
                'error' => (object) array(),
                'message' => 'Phone is already exist.',
            ], 201);
        }

        $user = User::where(['user_access_token' => $request->access_token])->first();
        if (!$user) {
            return response()->json([
                'status' => "0",
                'error' => (object) array(),
                'message' => trans('validation.invalid_access_token'),
            ], 201);
        }
        if ($user ) {           
            $user->temp_dial_code           = $request->dial_code;
            $user->temp_mobile              = $request->mobile;
            $user->user_phone_otp           = (string)get_otp();
            // $user->dial_code           = $request->dial_code;
            // $user->mobile              = $request->mobile;
            $user->phone_verified      = 0;
            $user->save();
            // $message    = $user->otp .' is verification code for change mobile request.';
            // $phone      = $user->temp_dial_code.$user->temp_mobile;
            // $sms_res    = send_SMS($message, $phone);

            // $otp = $user->otp;
            // $ShortDescription = 'Please use the below mentioned 4-digit code to verify your email & mobile <br>
            //                 <strong>'.$otp.'</strong>';
            // $Message = "splidu: Account verification code";
            // $mailbody = view('emails.chef.index',['Message' => $Message, 'otp' => $otp]);
            // send_email($user->email,'splidu OTP : '.$otp,$mailbody);

            $message = "OTP has been send successfully on your mobile";
            return response()->json([
                'status' => "1",
                'error' => (object) array(),
                'message' => $message,
            ], 200);

        }
        return response()->json([
            'status' => "0",
            'error' => (object) array(),
            'message' => trans('validation.invalid_access_token'),
        ], 201);
    }

    public function resend_mobile_otp(Request $request)
    {
        $rules = [
            'access_token' => 'required',
        ];
        $messages = [
            'access_token.required' => trans('validation.access_token_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $status = 0;
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
            return response()->json([
                'status' => "0",
                'message' => $message,
                'error' => (object) $errors,
            ], 200);
        }

        $user = User::where(['user_access_token' => $request->access_token])->first();
        if (!$user) {
            return response()->json([
                'status' => "0",
                'error' => (object) array(),
                'message' => trans('validation.invalid_access_token'),
            ], 201);
        }

        if($user){
            $user->user_phone_otp = (string)get_otp();
            $user->save();
            if($user->temp_dial_code && $user->temp_mobile){
                $message    = $user->user_phone_otp.'is verification code for change mobile request.';
                // $phone      = $user->temp_dial_code.$user->temp_mobile;
                // $sms_res    = send_SMS($message, $phone);

                $message = "OTP has been send successfully on your mobile";
                return response()->json([
                    'status' => "1",
                    'error' => (object) array(),
                    'message' => $message,
                ], 200);
            }
        }
        return response()->json([
            'status' => "0",
            'error' => (object) array(),
            'message' => 'Unable to send otp. Please try again.',
        ], 201);
    }
    
    public function change_mobile(Request $request)
    {
        $rules = [
            'access_token' => 'required',
            'otp' => 'required',
        ];
        $messages = [
            'access_token.required' => trans('validation.access_token_required'),
            'otp.required' => trans('validation.otp_required'),

        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $status = 0;
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
            return response()->json([
                'status' => "0",
                'message' => $message,
                'error' => (object) $errors,
            ], 200);
        }

        $user = User::where(['user_access_token' => $request->access_token])->first();
        if (!$user) {
            return response()->json([
                'status' => "0",
                'error' => (object) array(),
                'message' => trans('validation.invalid_access_token'),
            ], 201);
        }
        if ($user && $user->user_phone_otp == $request->otp) {
            $user->dial_code        = $user->temp_dial_code;
            $user->phone           = $user->temp_mobile;
            $user->phone_verified   = 1;
            $user->user_phone_otp = null;
            $user->save();
            
            return response()->json([
                'status' => "1",
                'error' => (object) array(),
                'message' =>'Your mobile number has been updated.',

            ], 200);
        } 
        return response()->json([
            'status' => "0",
            'error' => (object) array(),
            'message' => 'Unable to verify otp. Please try again.',
        ], 201);
    }

}

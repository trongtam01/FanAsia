<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/ads/googleads/v8/enums/user_list_combined_rule_operator.proto

namespace GPBMetadata\Google\Ads\GoogleAds\V8\Enums;

class UserListCombinedRuleOperator
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();
        if (static::$is_initialized == true) {
          return;
        }
        \GPBMetadata\Google\Api\Http::initOnce();
        \GPBMetadata\Google\Api\Annotations::initOnce();
        $pool->internalAddGeneratedFile(
            '
�
Dgoogle/ads/googleads/v8/enums/user_list_combined_rule_operator.protogoogle.ads.googleads.v8.enums"v
 UserListCombinedRuleOperatorEnum"R
UserListCombinedRuleOperator
UNSPECIFIED 
UNKNOWN
AND
AND_NOTB�
!com.google.ads.googleads.v8.enumsB!UserListCombinedRuleOperatorProtoPZBgoogle.golang.org/genproto/googleapis/ads/googleads/v8/enums;enums�GAA�Google.Ads.GoogleAds.V8.Enums�Google\\Ads\\GoogleAds\\V8\\Enums�!Google::Ads::GoogleAds::V8::Enumsbproto3'
        , true);
        static::$is_initialized = true;
    }
}


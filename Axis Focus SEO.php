<?php
/**
 * Plugin Name: 🎯 Axis Focus SEO
 * Plugin URI: https://erik.marketing
 * Description: Streamlined RankMath SEO workflow by focusing on relevant SEO metrics for law firms and solicitors. 
 * This lightweight plugin modifies RankMath's scoring system to prioritise core SEO elements,
 * while removing arbitrary auxiliary tests, resulting in a more focused and efficient content optimisation process for SEO Editors.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: ErikMarketing
 * Author URI: https://erik.marketing
 * License: GPL v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: axis-focus-seo
 * Domain Path: /languages
 *
 * @package AxisFocusSEO
 * @author ErikMarketing
 * @link https://erik.marketing
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 */
define('LITE_MATH_VERSION', '1.0.1');

/**
 * Class Lite_Math
 */
class Lite_Math {
    /**
     * Constructor
     */
    public function __construct() {
        // Only run if RankMath is active
        add_action('plugins_loaded', array($this, 'init'));
    }

    /**
     * Initialize the plugin
     */
    public function init() {
        if (!class_exists('RankMath')) {
            add_action('admin_notices', array($this, 'rank_math_missing_notice'));
            return;
        }

        // Add filter to modify RankMath tests
        add_filter('rank_math/researches/tests', array($this, 'modify_rank_math_tests'), 10, 2);
    }

    /**
     * Display admin notice if RankMath is not active
     */
    public function rank_math_missing_notice() {
        ?>
        <div class="notice notice-error">
            <p><?php _e('Lite Math requires RankMath SEO plugin to be installed and activated.', 'lite-math'); ?></p>
        </div>
        <?php
    }

    /**
     * Modify RankMath SEO score tests
     *
     * @param array $tests Array of tests
     * @param string $type Type of content being tested
     * @return array Modified tests array
     */
    public function modify_rank_math_tests($tests, $type) {
        // Keep essential SEO tests active
        $tests_to_disable = array(
            // Remove AI and automation-related tests
            'hasContentAI',              // Content AI test - not essential for SEO
            'titleHasNumber',            // Number in Title test - arbitrary rule
            'titleHasPowerWords',        // Power Words test - subjective
            'titleSentiment',            // Title Sentiment test - not core SEO
            
            // Remove overly specific or redundant tests
            'contentHasTOC',             // Table of Contents test - layout preference
            'hasProductSchema',          // Product Schema test - specific to ecommerce
            'isReviewEnabled',           // Review test - specific to certain content
            'keywordIn10Percent',        // Keyword in First 10% - arbitrary placement
            
            // Remove tests that might encourage over-optimization
            'keywordDensity',            // Keyword Density test - can lead to keyword stuffing
            'linksNotAllExternals',      // External Dofollow Link test - situation dependent
            
            // Keep these tests active as they're important for SEO:
            // - keywordInTitle
            // - keywordInContent
            // - keywordInMetaDescription
            // - keywordNotUsed
            // - contentHasAssets
            // - contentHasShortParagraphs
            // - isInternalLink
            // - keywordInImageAlt
            // - keywordInPermalink
            // - keywordInSubheadings
            // - lengthContent
            // - lengthPermalink
            // - linksHasDoFollow
            // - linksHasExternals
            // - linksHasInternal
            // - titleStartWithKeyword
        );

        foreach ($tests_to_disable as $test) {
            unset($tests[$test]);
        }

        return $tests;
    }
}

// Initialize the plugin
new Lite_Math();
<?php

use WPRA\App;
use WPRA\Helpers\Utils;
use WPRA\Integrations\WPML;

$params       = $start_counts = [];
$data_atts    = $social_platforms = $already = '';
$total_counts = 0;

if (isset($data)) {
    extract($data);
}

$reactions_classes = implode(' ', [
    $params['animation'] == 'true' ? 'wpra-animated-emojis' : 'wpra-static-emojis',
    'wpra-reactions-count-' . count($params['emojis']),
]);

$title_text         = WPML::getTranslation($params['sgc_id'], 'title_text', $params['title_text']);
$total_counts_title = WPML::getTranslation($params['sgc_id'], 'total_counts_label', $params['total_counts_label']);
?>
<div class="wpra-reactions-wrap wpra-plugin-container wpra-layout-disqus" <?php echo $data_atts; ?>>
    <div class="wpra-reactions-container">
        <div class="wpra-call-to-action"><?php echo $title_text; ?></div>
        <?php if ($params['show_total_counts'] == 'true'): ?>
            <div class="wpra-total-counts">
                <?php echo sprintf('<span>%s</span> %s', Utils::formatCount($total_counts), $total_counts_title); ?>
            </div>
        <?php endif; ?>
        <div class="wpra-reactions <?php echo $reactions_classes; ?>">
            <?php
            foreach ($params['emojis'] as $emoji_id):
                $emoji_count = isset($start_counts[$emoji_id]) ? $start_counts[$emoji_id] : 0;
                $flying_text = WPML::getTranslation($params['sgc_id'], "flying_labels_$emoji_id", $params['flying']['labels'][$emoji_id]);
                $start_counts_fmt = Utils::formatCount($emoji_count, $params['count_percentage'], $total_counts);
                $reaction_classes = $already == $emoji_id ? ["emoji-$emoji_id", 'active'] : ["emoji-$emoji_id"];
                $reaction_attrs = Utils::buildDataAttrs(['count' => $emoji_count, 'emoji_id' => $emoji_id]); ?>
                <div class="<?php echo implode(' ', $reaction_classes); ?> wpra-reaction" <?php echo $reaction_attrs; ?>>
                    <div class="wpra-flying"><?php echo $flying_text; ?></div>
                    <div class="wpra-reaction-wrap">
                        <?php Utils::renderTemplate(
                            'view/front/layouts/parts/single-emoji',
                            [
                                'animation'    => $params['animation'],
                                'emoji_id'     => $emoji_id,
                                'emoji_format' => $params['emoji_format'],
                                'is_lottie'    => $params['is_lottie'],
                            ]
                        ); ?>
                        <div class="wpra-reaction-label"><?php echo $flying_text; ?></div>
                    </div>
                    <div class="count-num"><?php echo $start_counts_fmt; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php echo $social_platforms; ?>
    </div> <!-- end of reactions container -->
</div> <!-- end of reactions wrap -->
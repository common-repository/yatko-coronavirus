<div class="widget widget_text">
    <div class="widget-content">
        <div class="feed-header">
            <?php if(isset($displayTitle) && $displayTitle): ?>
                <h2 class="feed-title widget-title subheading heading-size-3">
                    <?php if(isset($title)): ?>
                        <?php echo $title; ?>
                    <?php else: ?>
                        <?php _e( 'Coronavirus Update', 'text_domain' ); ?>
                    <?php endif; ?>
                </h2>
            <?php endif; ?>

            <div class="feed-selector blend-in">
                <select class="bounded">
                    <?php foreach ($feed['regions'] as $region => $data): ?>
                        <option value="<?php echo $region; ?>" <?php echo $selectedRegion === $region ? 'selected' : '' ?>>
                            <?php echo $this->prettyPrintKeyName($region); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
			<!--<div class="select__arrow"></div>-->
        </div>

        <div class="feed-text textwidget">
            <?php if((isset($selectedRegion) && $selectedRegion)): ?>
                <div class="center space-bottom">
					<div class="btn-group" role="group" aria-label="Cases Data">
						<button type="button" class="btn btn-danger"><span data-id="total_cases" title="Total Cases: <?php echo number_format_i18n($regionFeed['total_cases'],0); ?>"><?php echo number_format_i18n($regionFeed['total_cases'],0); ?></span> &nbsp; <span data-id="recovered" class="badge badge-success" title="Recovered: <?php echo number_format_i18n($regionFeed['recovered'], 0); ?>"><?php echo number_format_i18n($regionFeed['recovered'], 0); ?></span> &nbsp; <span data-id="deaths"  class="badge badge-secondary" title="Deaths: <?php echo number_format_i18n($regionFeed['deaths'], 0); ?>"><?php echo number_format_i18n($regionFeed['deaths'], 0); ?></span></button>
					</div>
				</div>
				<p class="<!--small-->">
					<strong class="strong">COVID-19 cases in <span data-id="region"><?php echo $this->prettyPrintKeyName($selectedRegion); ?></span>: <span data-id="total_cases"><?php echo number_format_i18n($regionFeed['total_cases'],0); ?></span></strong>. 
					Recovered: <span data-id="recovered"><?php echo number_format_i18n($regionFeed['recovered'], 0); ?></span>. Casualties: 
                    <span data-id="deaths"><?php echo number_format_i18n($regionFeed['deaths'], 0); ?></span>. Active cases: <span data-id="active_cases"><?php echo number_format_i18n($regionFeed['total_cases'] - $regionFeed['recovered'] - $regionFeed['deaths'], 0); ?></span>. 
					Death ratio <span data-id="death_ratio"><?php echo number_format_i18n($regionFeed['death_ratio']*100, 2).'%'; ?></span> and <span data-id="recovery_ratio"><?php echo number_format_i18n($regionFeed['recovery_ratio']*100, 2).'%'; ?></span> recovering.
				</p>
            <?php else: ?>
                <p>No data available for this region :)</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {})
</script>
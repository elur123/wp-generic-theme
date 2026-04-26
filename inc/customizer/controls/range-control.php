<?php
declare(strict_types=1);
/**
 * Range slider Customizer control
 *
 * @package MedSpaStarter
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

class Medspastarter_Range_Control extends WP_Customize_Control {

	/** @var string */
	public $type = 'medspastarter-range';

	/** @var array<string,int> */
	public $input_attrs = [
		'min'  => 0,
		'max'  => 100,
		'step' => 1,
	];

	public function enqueue(): void {
		wp_add_inline_script( 'customize-controls', <<<'JS'
		(function(){
			document.addEventListener('input', function(e){
				if(e.target.classList.contains('msp-range-input')){
					var out = e.target.parentNode.querySelector('.msp-range-value');
					if(out) out.textContent = e.target.value;
				}
			});
		})();
		JS );
	}

	public function render_content(): void {
		$min  = (int) ( $this->input_attrs['min']  ?? 0 );
		$max  = (int) ( $this->input_attrs['max']  ?? 100 );
		$step = (int) ( $this->input_attrs['step'] ?? 1 );

		if ( ! empty( $this->label ) ) {
			echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
		}
		if ( ! empty( $this->description ) ) {
			echo '<span class="description customize-control-description">' . esc_html( $this->description ) . '</span>';
		}
		?>
		<div style="display:flex;align-items:center;gap:10px;">
			<input
				type="range"
				class="msp-range-input"
				min="<?php echo esc_attr( (string) $min ); ?>"
				max="<?php echo esc_attr( (string) $max ); ?>"
				step="<?php echo esc_attr( (string) $step ); ?>"
				value="<?php echo esc_attr( (string) $this->value() ); ?>"
				style="flex:1;"
				<?php $this->link(); ?>
			>
			<output class="msp-range-value" style="min-width:2rem;text-align:center;"><?php echo esc_html( (string) $this->value() ); ?></output>
		</div>
		<?php
	}
}

<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Bottom extends Field
{
	public $name = 'Wezwanie do działania';
	public $description = 'Bottom';
	public $slug = 'bottom';
	public $category = 'formatting';
	public $icon = 'email';
	public $keywords = ['formularz', 'zdjecie'];
	public $mode = 'edit';
	public $supports = [
		'align' => false,
		'mode' => false,
		'jsx' => true,
	];

	public function fields(): array
	{
		$bottom = new FieldsBuilder('bottom');

		$bottom
			->setLocation('options_page', '==', 'bottom') // ważne!
			/*--- FIELDS ---*/
			->addTab('Treść', ['placement' => 'top'])
			->addTab('Kafelki', ['placement' => 'top'])
			->addRepeater('r_bottom', [
				'label' => 'Kafelki',
				'layout' => 'table', // 'row', 'block', albo 'table'
				'min' => 1,
				'button_label' => 'Dodaj kafelek'
			])
			->addImage('image', [
				'label' => 'Obraz',
				'return_format' => 'array', // lub 'url', lub 'id'
				'preview_size' => 'thumbnail',
			])
			->addText('title', [
				'label' => 'Nagłówek',
			])
			->addTextarea('text', [
				'label' => 'Opis',
			])
			->endRepeater()

			/*--- USTAWIENIA BLOKU ---*/

			->addTab('Ustawienia bloku', ['placement' => 'top'])
			->addTrueFalse('flip', [
				'label' => 'Odwrotna kolejność',
				'ui' => 1,
				'ui_on_text' => 'Tak',
				'ui_off_text' => 'Nie',
			])
			->addText('section_id', [
				'label' => 'ID',
			])
			->addText('section_class', [
				'label' => 'Dodatkowe klasy CSS',
			]);


		return [$bottom];
	}
}

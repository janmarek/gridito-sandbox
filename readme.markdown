Gridito
=======

- Autor: Jan Marek
- Licence: MIT

Datagrid pro Nette a jQuery UI.

Demo
----

http://griddemo.janmarek.net/document_root/

Vyžaduje
--------

- Nette Framework pro PHP 5.3 se jmennými prostory
- jQuery 1.4+
- jQuery UI (CSS, jQuery UI Dialog, jQuery UI Button)
- jQuery LiveQuery plugin
- Nette jQuery Ajax plugin

Použití
-------

a) Načíst JS a CSS soubory Gridita.
b) Ukázkový presenter:

	use Nette\Environment;
	use Nette\Forms\Form;
	use Nette\Application\AppForm;
	use Gridito\Grid;
	use Gridito\OrmionModel;

	class GridPresenter extends BasePresenter {


		protected function createComponentGrid($name) {
			$grid = new Grid;
			$presenter = $this;

			$grid->model = new OrmionModel(Color::findAll());
			$grid->setItemsPerPage(5);

			$grid->addColumn("hash", "Hexadecimální kód", array(
				'renderer' => function ($record) {
					echo "<span style='color:#$record->hash'>#$record->hash</span>";
				},
				'sortable' => true,
			));
			$grid->addColumn("description", "Popis");
			$grid->addColumn("nice", "Je krásná")->setSortable(true);
			$grid->addColumn("created", "Vytvořeno")->setSortable(true);

			$grid->addToolbarWindowButton("add", "Přidat", array(
				'handler' => function () use ($presenter) {
					$presenter["addForm"]->render();
				},
				'icon' => 'plusthick',
			));

			$grid->addWindowButton("edit", "Upravit", array(
				'handler' => function ($record) use ($presenter) {
					$presenter["editForm"]->setDefaults($record);
					$presenter["editForm"]->render();
				},
				'icon' => 'pencil',
			));

			$grid->addButton("delete", "Smazat", array(
				'handler' => function ($record) use ($grid) {
					$record->delete();
					$grid->flashMessage("Barva byla smazána.");
				},
				'icon' => 'closethick',
				'confirmationQuestion' => function ($record) {
					return "Opravdu smazat barvu #$record->hash?"
				},
			));

			return $grid;
		}


		private function formBase($name, $new) {
			$form = new AppForm($this, $name);

			if (!$new) $form->addHidden("id");

			$form->addText("hash", "Hexadecimální kód", 6, 6)
				->addRule(Form::REGEXP, "Použijte šest znaků 0-F", "/[0-9A-Fa-f]{6}/");
			$form->addText("description", "Popis", 30, 100);
			$form->addCheckbox("nice", "Je krásná");
			$form->addSubmit("s", "Uložit");

			$grid = $this["grid"];

			$form->onSubmit[] = function ($form) use ($grid) {
				Color::create($form->values)->save();
				$grid->flashMessage("Barva byla uložena.");
				$grid->redirect("this");
			};
		}


		protected function createComponentAddForm($name) {
			$this->formBase($name, true);
		}


		protected function createComponentEditForm($name) {
			$this->formBase($name, false);
		}

	}
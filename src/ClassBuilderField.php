<?

class ClassBuilderField extends ClassBuilderNode {
	protected $access  = ClassBuilder::ACCESS_PUBLIC;
	protected $static  = false;
	protected $default = null;

	function __construct($name) {
		parent::__construct($name);
		$this->addPHPDoc('var', '');
	}

	function __toString() {
		$s = $this->getComment();

		if (ClassBuilder::ACCESS_PUBLIC!=$this->access) {
			$s .= "{$this->access} ";
		}
		if ($this->static) {
			$s .= "{$this->static} ";
		}
		$s .= $this->name;
		if ($this->default) {
			$s .= "= {$this->default}";
		}
		return $s.";\n";
	}
}

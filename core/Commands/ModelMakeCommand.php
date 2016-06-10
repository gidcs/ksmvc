<?php

/**
 * KSMVC version 0.1.0
 * https://github.com/gidcs/ksmvc
 * Lim Kok Suan <admin@gidcs.net>
 */

namespace Core\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ModelMakeCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('make:model')
            ->setDescription('Create a new model class.')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'What is your model name?'
            )
            ->addOption(
                'blank',
                null,
                InputOption::VALUE_NONE,
                'If set, the new model class will be blank.'
            );
    }

	protected function getErrorString($error_header='',$error_msg=[]){
			$maxlen = max(array_map('strlen', $error_msg));
			$maxlen = max($maxlen,strlen($error_header));
			$text = '<error>'."\n";
			$text .= '  '.str_repeat(' ', $maxlen)."  \n";
			$text .= '  '.$error_header.str_repeat(' ', $maxlen-strlen($error_header))."  \n";
			foreach($error_msg as $msg){
				$text .= '  '.$msg.str_repeat(' ', $maxlen-strlen($msg))."  \n";
			}
			$text .= '  '.str_repeat(' ', $maxlen)."  ";
			$text .= '</error>';
			return $text;
	}
	
	protected function copy_file($source, $dest, $name){
		$file = file_get_contents($source);
		$file = str_replace("DummyClass", $name, $file);
		return file_put_contents($dest, $file, LOCK_EX);
	}
	
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = ucfirst($input->getArgument('name'));
		$source = './core/Commands/stub/model.stub';
		if ($input->getOption('blank')) {
            $source .= '.plain';
        }
		$dest = './app/models/'.$name.'.php';
		if(file_exists($dest)){
			$header = '[InvalidArgumentException]';
			$msg[] = 'The model class "'.$name.'" already exists';
			$text=$this->getErrorString($header,$msg);
		}
		else{
			if(!file_exists($source)){
				$header = '[MissingFileException]';
				$msg[] = 'Failed to write file to app/models/'.$name.'.php';
				$text=$this->getErrorString($header,$msg);
			}
			else if (!$this->copy_file($source,$dest,$name)){
				$header = '[CopyFailedException]';
				$msg[] = 'Failed to write file to app/models/'.$name.'.php  ';
				$text=$this->getErrorString($header,$msg);
			}
			else{
				$text = '<info>created</info> app/models/'.$name.'.php';
			}
		}
        $output->writeln($text);
    }
}

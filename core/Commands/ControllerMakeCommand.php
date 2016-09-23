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

class ControllerMakeCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('make:controller')
            ->setDescription('Create a new controller class.')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'What is your controller name?'
            )
            ->addOption(
                'blank',
                null,
                InputOption::VALUE_NONE,
                'If set, the new controller class will be blank.'
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
	
	protected function copy_controller_file($source, $dest, $name, $view_dir_name){
		$file = file_get_contents($source);
		$file = str_replace("DummyClass", $name, $file);
		$file = str_replace("Dummy", $view_dir_name, $file);
		return file_put_contents($dest, $file, LOCK_EX);
	}

	protected function copy_view_file($source, $dest){
		$file = file_get_contents($source);
		return file_put_contents($dest, $file, LOCK_EX);
	}
	
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //for view files creation
		$view_dir_name = lcfirst($input->getArgument('name'));
		$view_dir = './app/views/'.$view_dir_name;
		
		//for controller file creation
		$name = ucfirst($input->getArgument('name')).'Controller';
		$source = './core/Commands/stub/controller.stub';
		if ($input->getOption('blank')) {
            $source .= '.plain';
        }
		$dest = './app/controllers/'.$name.'.php';
		if(file_exists($dest)){
			$header = '[InvalidArgumentException]';
			$msg[] = 'The controller class "'.$name.'" already exists';
			$text=$this->getErrorString($header,$msg);
			$output->writeln($text);
		}
		else{
			//generate controller file
			if(!file_exists($source)){
				$header = '[MissingFileException]';
				$msg[] = 'Failed to write file to app/controllers/'.$name.'.php';
				$text=$this->getErrorString($header,$msg);
				$output->writeln($text);
			}
			else if (!$this->copy_controller_file($source,$dest,$name,$view_dir_name)){
				$header = '[CopyFailedException]';
				$msg[] = 'Failed to write file to app/controllers/'.$name.'.php  ';
				$text=$this->getErrorString($header,$msg);
				$output->writeln($text);
			}
			else{
				$text = '<info>created</info> app/controllers/'.$name.'.php';
				$output->writeln($text);
				//generate view files
				if ($input->getOption('blank')) {
					//no view files needed
				}
				else{
					//create directory
					if(!mkdir($view_dir, 0755)){
						$header = '[MkdirFailedException]';
						$msg[] = 'Failed to create directory app/views/'.$view_dir_name;
						$text=$this->getErrorString($header,$msg);
						$output->writeln($text);
					}
					else{
						$text = '<info>directory created</info> app/views/'.$view_dir_name;
						$output->writeln($text);
						//copy view file
						$views = ['index', 'create', 'show', 'edit'];
						$source = './core/Commands/stub/view.stub';
						foreach($views as $v){
							$dest = $view_dir.'/'.$v.'.php';
							if(!$this->copy_view_file($source,$dest)){	
								$header = '[CopyFailedException]';
								$msg[] = 'Failed to write file to app/views/'.$view_dir_name.'/'.$v.'.php  ';
								$text=$this->getErrorString($header,$msg);
								$output->writeln($text);
							}
							else{
								$text = '<info>created</info> app/views/'.$view_dir_name.'/'.$v.'.php';
								$output->writeln($text);
							}
						}
					}
				}
			}
		}
    }
}

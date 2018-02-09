<?php 
namespace CoinSystem;

class Commands implements CommandExecutor{
    
    public void onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
        if($cmd->getName() == "coins"){
            
        }
    }
}
?>
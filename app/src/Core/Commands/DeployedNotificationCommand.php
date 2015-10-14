<?php namespace App\Core\Commands;

use Slack;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class DeployedNotificationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:deployed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a message to Slack notifying about new deployments';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $instance = $this->argument('instance');
        $branch = $this->argument('branch');
        $build_env = $this->argument('build_env');

        $emoji = [
            ':shipit:',
            ':sweat_drops:',
            ':notes:',
            ':musical_note:',
            ':fire:',
            ':hankey:',
            ':poop:',
            ':shit:',
            ':+1:',
            ':thumbsup:',
            ':-1:',
            ':thumbsdown:',
            ':ok_hand:',
            ':punch:',
            ':facepunch:',
            ':fist:',
            ':v:',
            ':wave:',
            ':hand:',
            ':raised_hand:',
            ':open_hands:',
            ':point_up:',
            ':point_down:',
            ':point_left:',
            ':point_right:',
            ':raised_hands:',
            ':pray:',
            ':point_up_2:',
            ':clap:',
            ':muscle:',
            ':metal:',
            ':fu:',
            ':runner:',
            ':running:',
            ':couple:',
            ':family:',
            ':two_men_holding_hands:',
            ':two_women_holding_hands:',
            ':dancer:',
            ':dancers:',
            ':ok_woman:',
            ':no_good:',
            ':information_desk_person:',
            ':raising_hand:',
            ':bride_with_veil:',
            ':person_with_pouting_face:',
            ':person_frowning:',
            ':bow:',
            ':couplekiss:',
            ':couple_with_heart:',
            ':massage:',
            ':haircut:',
            ':nail_care:',
            ':boy:',
            ':girl:',
            ':woman:',
            ':man:',
            ':baby:',
            ':older_woman:',
            ':older_man:',
            ':person_with_blond_hair:',
            ':man_with_gua_pi_mao:',
            ':man_with_turban:',
            ':construction_worker:',
            ':cop:',
            ':angel:',
            ':princess:',
            ':smiley_cat:',
            ':smile_cat:',
            ':heart_eyes_cat:',
            ':kissing_cat:',
            ':smirk_cat:',
            ':scream_cat:',
            ':crying_cat_face:',
            ':joy_cat:',
            ':pouting_cat:',
            ':japanese_ogre:',
            ':japanese_goblin:',
            ':see_no_evil:',
            ':hear_no_evil:',
            ':speak_no_evil:',
            ':guardsman:',
            ':skull:',
            ':feet:',
            ':lips:',
            ':kiss:',
            ':droplet:',
            ':ear:',
            ':eyes:',
            ':nose:',
            ':tongue:',
            ':love_letter:',
            ':bust_in_silhouette:',
            ':busts_in_silhouette:',
            ':speech_balloon:',
            ':thought_balloon:',
            ':feelsgood:',
            ':finnadie:',
            ':goberserk:',
            ':godmode:',
            ':hurtrealbad:',
            ':rage1:',
            ':rage2:',
            ':rage3:',
            ':rage4:',
            ':suspect:',
            ':trollface:',
        ];

        Slack::send("Server `$instance` has been deployed at branch `$branch` (build environment = `$build_env`)".$emoji[array_rand($emoji)]);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('instance', InputArgument::REQUIRED, 'Name of the deployed instance'),
            array('branch', InputArgument::REQUIRED, 'The branch deployed'),
        );
    }
}

# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
    config.vm.define :codebox do |lv4_config|
        lv4_config.vm.box = "precise32"
        # lv4_config.vm.box_url = "http://files.vagrantup.com/precise32.box"
        # Uncomment the line below if you have local precise32.box file
        lv4_config.vm.box_url = "precise32.box"
        lv4_config.ssh.forward_agent = true

        # This will give the machine a static IP uncomment to enable
        lv4_config.vm.network :private_network, ip: "192.168.33.10"

        lv4_config.vm.network :forwarded_port, guest: 80, host: 8888, auto_correct: true
        lv4_config.vm.network :forwarded_port, guest: 3306, host: 8889, auto_correct: true
        lv4_config.vm.network :forwarded_port, guest: 5432, host: 5433, auto_correct: true
        lv4_config.vm.hostname = "laravel"
        lv4_config.vm.synced_folder "wwwroot", "/var/www", {:mount_options => ['dmode=777','fmode=777']}
        lv4_config.vm.provision :shell, :inline => "echo \"Europe/Helsinki\" | sudo tee /etc/timezone && dpkg-reconfigure --frontend noninteractive tzdata"

        lv4_config.vm.provider :virtualbox do |v|
            v.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
            v.customize ["modifyvm", :id, "--memory", "512"]
            v.gui = true
        end

        lv4_config.vm.provision :puppet do |puppet|
            puppet.manifests_path = "puppet/manifests"
            puppet.manifest_file  = "phpbase.pp"
            puppet.module_path = "puppet/modules"
            #puppet.options = "--verbose --debug"
        end
    end
end

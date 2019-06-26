import React from 'react';

import SidebarMenuItem from './SidebarMenuItem';

const SideBarMenu = () => (
    <ul className="sidebar-menu" data-widget="tree">
        <li className="header">MAIN NAVIGATION</li>
        <SidebarMenuItem link='/client/films' icon='fa fa-tachometer' itemName='Dashboard'/> 
        <SidebarMenuItem link='/clients/posts' icon='fa fa-th-list' itemName='Posts'/> 
    </ul>
);

export default SideBarMenu;

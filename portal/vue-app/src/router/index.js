import Vue from 'vue';
import Router from 'vue-router';
import Home from '@/views/Home';
import Tickets from '@/views/Tickets';
import Faq from '@/views/Faq';
import Invoices from '@/views/Invoices';
import Documents from '@/views/Documents';
import DocumentShow from '@/views/DocumentShow';
import Login from '@/views/Login';
import TicketShow from "@/views/TicketShow";
import InvoiceShow from "@/views/InvoiceShow";
import Profile from "@/views/Profile";
import Index from "@/views/Index";
import EntityShow from "@/views/EntityShow";

Vue.use(Router)

export default new Router({
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/tickets',
            name: 'HelpDesk',
            component: Tickets,
            props: true
        },
        {
            path: '/faq',
            name: 'Faq',
            component: Faq
        },
        {
            path: '/invoices',
            name: 'Invoice',
            component: Invoices
        },
        {
            path: '/products',
            name: 'Products',
            component: Index
        },
        {
            path: '/product/:id',
            name: 'Products-show',
            component: EntityShow,
            props: true
        },
        {
            path: '/services',
            name: 'Services',
            component: Index
        },
        {
            path: '/product/:id',
            name: 'Services-show',
            component: EntityShow,
            props: true
        },
        {
            path: '/documents',
            name: 'Documents',
            component: Documents
        },
        {
            path: '/document/:id',
            name: 'DocumentShow',
            component: DocumentShow,
            props: true
        },
        {
            path: '/assets',
            name: 'Assets',
            component: Index
        },
        {
            path: '/projects',
            name: 'Project',
            component: Index
        },
        {
            path: '/project/:id',
            name: 'Project-show',
            component: EntityShow,
            props: true
        },
        {
            path: '/projecttasks',
            name: 'ProjectTask',
            component: Index
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/ticket/:id',
            name: 'ticket-show',
            component: TicketShow,
            props: true
        },
        {
            path: '/invoice/:id',
            name: 'invoice-show',
            component: InvoiceShow,
            props: true
        },
        {
            path: '/myprofile',
            name: 'profile',
            component: Profile
        }
    ],
    // mode: 'history'
})
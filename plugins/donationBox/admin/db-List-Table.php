<?php

/* 
 * Create my custom table/lits based in WordPress.
 */

if( ! class_exists( 'WP_List_Table' ) )
{
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class db_List_Table extends WP_List_Table
{
    
    public $data = array();


    public function __construct( $args )
    {

        parent::__construct();

        foreach ($args as $cron_job) 
        {
            if ( $cron_job['db_cron_hook_insert_update'] || $cron_job['db_cron_hook_delete'])
            {
                foreach ($cron_job as $value)
                {
                    foreach ($value as $temp )
                    {
                        $current_array = array
                            (
                            'project_id'    => $temp['args'][0],
                            'title'         => get_the_title($temp['args'][0]),
                            'request'       => $this->set_right_html( $temp['args'][1] ),
                            'time'          => $temp['args'][2]
                            );

                        array_push($this->data, $current_array);
                    }
                }
            }
        }

    }
    
    
    
    
    
    public function get_columns()
    {
        $columns = array
        (
            'project_id'    => 'ID',
            'title'         => 'Donation Project Title',
            'request'       => 'Request for',
            'time'          => 'Νext attempt'
        );
        return $columns;
    }


    
    
    
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $this->data;
    }
    
    
    
    
    
    protected function column_default( $item, $column_name )
    {
        switch( $column_name )
        {
            case 'project_id':
            case 'title':
            case 'request':
            case 'time':
            return $item[ $column_name ];
            default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }
    
    
    
    
    
    /**
     * @return integer : The total number of items in table/list.
     */
    
    function get_total_items()
    {
        return count($this->data);
    }
    
    
    
    
    
    /**
     * Show in Bootstrap label the type of request.
     * 
     * @param boolean $status : The request status.
     *                        - true : for insert/update
     *                        - false : for delete.
     * 
     * @return string : The HTML code which includes the appropriate message
     * inside an label.
     */
    
    private function set_right_html( $status )
    {
        if ( $status )
        {
            return '<h6><span class="label label-primary">Insert/Update</span></h6>';
        }
        else
        {
            return '<h6><span class="label label-danger">Delete</span></h6>';
        }
        
    }


}